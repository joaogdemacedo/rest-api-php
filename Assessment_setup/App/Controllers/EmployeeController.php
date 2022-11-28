<?php

namespace App\Controllers;

use App\Plugins\Http\Response\Created;
use App\Plugins\Http\Response\Ok;
use App\Services\AuthenticationService;
use App\Services\EmployeeService;
use App\Utils\Utils;

class EmployeeController extends AuthenticationController
{
    private EmployeeService $employeeService;
    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $dbConnection = Utils::getDbConnection();
        $this->employeeService = new EmployeeService($dbConnection);
        $this->authenticationService = new AuthenticationService($dbConnection);
    }


    public function createEmployee()
    {
        $employee = $this->employeeService->createEmployee(Utils::getDecodeJson());
        return (new Created($employee))->send();
    }


    public function listEmployees()
    {
        $employeeId = $this->authenticationService->validateToken();

        $employees = $this->employeeService->listEmployees();
        return (new Ok($employees))->send();
    }


    public function getEmployee(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();

        $employee = $this->employeeService->getEmployee($id);
        return (new Ok($employee))->send();
    }


    public function updateEmployee(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();

        $employee = $this->employeeService->updateEmployee($id, Utils::getDecodeJson());
        return (new Ok($employee))->send();
    }


    public function deleteEmployee(int $id)
    {
        $employeeId = $this->authenticationService->validateToken();

        $employee = $this->employeeService->deleteEmployee($id);
        return (new Ok($employee))->send();
    }
}