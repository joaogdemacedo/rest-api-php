<?php

namespace App\Controllers;

use App\Plugins\Http\Exceptions\BadRequest;
use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Plugins\Http\Exceptions\Unauthorized;
use App\Plugins\Http\Response\Created;
use App\Plugins\Http\Response\Ok;
use App\Services\AuthenticationService;
use App\Services\EmployeeService;

class EmployeeController extends AuthenticationController
{
    private EmployeeService $employeeService;
    private AuthenticationService $authenticationService;


    public function __construct(){
        $this->employeeService = new EmployeeService();
        $this->authenticationService = new AuthenticationService();
    }



    public function createEmployee()
    {
        $getBody = file_get_contents('php://input');
        $decodeJson = json_decode($getBody, true);

        $employee = $this->employeeService->createEmployee($decodeJson);
        return (new Created($employee))->send();
    }



    public function listEmployees(){
        $employeeId = $this->authenticationService->validateToken();

        $employees = $this->employeeService->listEmployees();
        return (new Ok($employees))->send();
    }



    public function getEmployee(int $id){
        $employeeId = $this->authenticationService->validateToken();

        $employee = $this->employeeService->getEmployee($id);
        return (new Ok($employee))->send();
    }



    public function updateEmployee(int $id){
        $employeeId = $this->authenticationService->validateToken();

        $getBody = file_get_contents('php://input');
        $decodeJson = json_decode($getBody, true);

        $employee = $this->employeeService->updateEmployee($id, $decodeJson);
        return (new Ok($employee))->send();
    }



    public function deleteEmployee(int $id){
        $employeeId = $this->authenticationService->validateToken();

        $employee = $this->employeeService->deleteEmployee($id);
        return (new Ok($employee))->send();
    }
}