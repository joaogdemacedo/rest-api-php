<?php

namespace App\Services;

use App\Exceptions\EmployeeNotFoundException;
use App\Plugins\Db\Db;
use App\Plugins\Http\Exceptions\BadRequest;
use App\Plugins\Http\Exceptions\NotFound;
use App\Plugins\Http\Exceptions\Unauthorized;
use App\Repositories\EmployeeRepository;
use DateTimeImmutable;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthenticationService
{
    // A secret key should be placed as an environment variable
    private string $secretKey = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    private EmployeeRepository $employeeRepository;
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
        $this->employeeRepository = new EmployeeRepository($this->db);
    }


    // Authentication with basic JWT implementation
    public function authenticate(array $requestPayload): string
    {
        $username = $requestPayload['username'];
        $password = $requestPayload['password'];

        try {
            $employee = $this->employeeRepository->getEmployeeByUsername($username);
        } catch (EmployeeNotFoundException $exception) {
            throw new NotFound(['message' => 'Unable to find this User.']);
        }

        if (!password_verify($password, $employee->getPassword())) {
            throw new Unauthorized(['message' => 'Invalid credentials.']);
        }

        $issuedAt = new DateTimeImmutable();
        // Token valid during 1 day in testing mode
        $expire = $issuedAt->modify('+1 day')->getTimestamp();
        $username = $employee->getUsername();

        $data = [
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $expire,
            'userName' => $username,
            'id' => $employee->getId()
        ];

        return JWT::encode(
            $data,
            $this->secretKey,
            'HS512'
        );
    }


    public function validateToken()
    {
        $headers = getallheaders();
        if (!array_key_exists('Authorization', $headers)) {
            throw new BadRequest(['message' => 'Authorization header not specified.']);
        }

        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {

            try {
                $token = JWT::decode($matches[1], new Key($this->secretKey, 'HS512'));
            } catch (ExpiredException $exception){
                throw new Unauthorized(['message' => 'Token has expired.']);
            }
            // Returning the Employee ID, allowing to know who made each request.
            $data = (array) $token;
            return $data['id'];
        }
    }

}