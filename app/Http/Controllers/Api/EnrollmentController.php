<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\EnrollmentRepository;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\Payment;
use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;


class EnrollmentController extends Controller
{
    protected $enrollmentRepository;

    public function __construct(EnrollmentRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

  
    // public function enroll(Request $request, $courseId)
    // {
    //     $userId = auth()->id(); 
    //     $enrollment = $this->enrollmentRepository->enroll($courseId, $userId);

    //     if (!$enrollment) {
    //         return response()->json(['message' => 'User is already enrolled'], 400);
    //     }

    //     return response()->json(['message' => 'Enrollment successful', 'enrollment' => $enrollment], 201);
    // }

    public function enroll(Request $request, $course_id)
    {
        
        $userId = Auth::id();

        $paymentExists = Payment::where('user_id', $userId)
            ->where('course_id', $course_id)
            ->where('payment_status', 'payed')
            ->exists();

        if ($paymentExists) {
            return response()->json(['message' => 'Vous êtes déjà inscrit à ce cours'], 409);
        }

        Stripe::setApiKey(env('STRIPE_TEST_SK'));

        $course = Course::findOrFail($course_id);
        
        $session = Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'mad',
                        'product_data' => [
                            'name' => $course->title,
                        ],
                        'unit_amount'  => $course->price * 100,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('payment.success', $course_id),
            'cancel_url'  => route('payment.checkout', $course_id),
        ]);

        Payment::create([
            'user_id' => $userId,
            'course_id' => $course_id,
            'amount' => $course->price,
            'payment_status' => "pending",
            'transaction_id' => $session->id,
            'payment_method'=>"stripe"
        ]);

        session()->put('Session_token_payment', $session->id);

        return response()->json([
            'message' => 'Redirection vers Stripe pour le paiement',
            'payment_url' => $session->url
        ]);
    }


    public function getEnrollmentsByCourse($courseId)
    {
        $enrollments = $this->enrollmentRepository->getEnrollmentsByCourse($courseId);
        return response()->json(['enrollments' => $enrollments]);
    }

    
    public function updateEnrollmentStatus(Request $request, $enrollmentId)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $enrollment = $this->enrollmentRepository->updateEnrollmentStatus($enrollmentId, $request->status);
        return response()->json(['message' => 'Enrollment status updated', 'enrollment' => $enrollment]);
    }

    
    public function deleteEnrollment($enrollmentId)
    {
        $this->enrollmentRepository->deleteEnrollment($enrollmentId);
        return response()->json(['message' => 'Enrollment deleted']);
    }

    public function paymentHistory()
    {
        try {
            $user = Auth::user();
            
            $payments = Payment::where('user_id', $user->id)
                ->with(['course' => function($query) {
                    $query->withTrashed()->select('id', 'title', 'price');
                }])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'status' => $payment->payment_status,
                        'method' => $payment->payment_method,
                        'transaction_id' => $payment->transaction_id,
                        'date' => $payment->created_at->format('Y-m-d H:i:s'),
                        'course' => $payment->course ? [
                            'id' => $payment->course->id,
                            'title' => $payment->course->title,
                            'price' => $payment->course->price
                        ] : null
                    ];
                });
            
            return response()->json([
                'success' => true,
                'payments' => $payments
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}