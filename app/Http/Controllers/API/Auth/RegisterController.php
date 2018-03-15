<?php

namespace lumenous\Http\Controllers\API\Auth;

use lumenous\User;
use lumenous\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use lumenous\Repositories\Interfaces\UsersRepositoryInterface;

class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     *
     * @var UsersRepositoryInterface 
     */
    protected $usersRepository;

    /**
     * Create a new controller instance.
     *
     * @param UsersRepositoryInterface $usersRepository
     * @return void
     */
    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->middleware('guest');
        $this->usersRepository = $usersRepository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data,
                               [
                    'email' => 'required|string|email|max:255|unique:users|confirmed',
                    'stellar_public_key' => 'required|string|max:255',
                    'password' => array(
                        'required', 'min:8',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
                    )],
                               [
                    'password.regex' => 'Password must contain uppercase, lowercase, number, a special character, and be at least 8 characters in length'
                        ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \lumenous\User
     */
    protected function create(array $data)
    {
        $user = $this->usersRepository->updateOrCreateByStellarPublicKey($data['stellar_public_key'], $data);

        // Create verification code fro email verification
        $user->verification_code()->create(['code' => str_random(30)]);

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(\Illuminate\Http\Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user) ?: response()->json(['message' => 'Registration Successful. Please check your inbox to verify your email.']);
    }

}
