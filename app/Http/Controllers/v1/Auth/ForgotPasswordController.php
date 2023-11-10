<?php
namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\ForgotPassword\ForgotPasswordRequest;
use App\Http\Transformers\v1\UserTransformer;
use App\Notifications\ForgetPassword;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Src\Auth\Facades\PasswordResetRepository;
use Src\Auth\PasswordReset;
use Src\People\Facades\SystemUserRepository;
use Src\People\SystemUser;
use Src\People\User;

class ForgotPasswordController extends Controller
{
    /**
     * Trigger forget password email
     * @group Forget Password Module
     *
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * @bodyParam email string required
     */
    public function forgot(ForgotPasswordRequest $request)
    {
        $email = $request->input('email');
        $user = User::where('email', '=', $email)->first();

        if($user) {
            $token = Uuid::uuid4()->toString();

            PasswordResetRepository::createToken($email, $token);

            $user->notify(new ForgetPassword($token));
        }

        $success['status'] = "Request success. You will receive an email if you are a registered user.";

        return response()->json(['data' => $success], 200);
    }

    /**
     * Reset password action
     * @group Forget Password Module
     *
     * @param Request $request
     * @param $tokenid
     * @return \Dingo\Api\Http\Response|void
     * @throws \Exception
     *
     * @bodyParam password string required
     * @bodyParam confirm_password string required
     */
    public function validateToken(Request $request, $tokenid)
    {
        $passwordReset = PasswordReset::where('token', $tokenid)->firstOrFail();

        $user = SystemUser::where('email', $passwordReset->email)->firstOrFail();

        $newPassword        = $request->input('password');
        $confirmedPassword  = $request->input('confirm_password');

        if($newPassword === $confirmedPassword) {
            SystemUserRepository::updatePassword($user, $newPassword);
            PasswordReset::where('token', '=', $tokenid)->delete();

            return $this->response->item($user, new UserTransformer);
        }
        else {
            return $this->response->error("New password is not same as confirmed password", 400);
        }
    }
}
