<?php

namespace App\Services;

use App\Entities\Employee;
use App\Exceptions\EmployeeAlreadyExistsException;
use App\Exceptions\EmployeeNotFoundException;
use App\Exceptions\FacilityNotFoundException;
use App\Plugins\Http\Exceptions\Conflict;
use App\Plugins\Http\Exceptions\NotFound;
use App\Repositories\EmployeeRepository;
use App\Repositories\FacilityRepository;

class EmployeeService
{
    private EmployeeRepository $employeeRepository;
    private FacilityRepository $facilityRepository;

    public function __construct()
    {
        $this->employeeRepository = new EmployeeRepository();
        $this->facilityRepository = new FacilityRepository();
    }


    public function createEmployee(array $requestPayload): Employee
    {
        /** @var string $username */
        $username = $requestPayload['username'];

        /** @var string $password */
        $password = $requestPayload['password'];

        /** @var int $facilityId */
        $facilityId = $requestPayload['facility_id'];

        $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
        try {
            $this->facilityRepository->getFacility($facilityId);
            $employee = $this->employeeRepository->createEmployee($username, $passwordHash, $facilityId);
        } catch (EmployeeAlreadyExistsException $exception){
            throw new Conflict(['message' => 'Username already exists.']);
        } catch (FacilityNotFoundException $e) {
            throw new NotFound(['message' => 'Unable to find Facility.']);

        }
        return $employee;
    }


    public function listEmployees(): array
    {
        try {
            $employees = $this->employeeRepository->listEmployees();
        } catch (EmployeeNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find Employees.']);
        }
        return $employees;
    }


    public function getEmployee(int $id): Employee
    {
        try {
            $employee = $this->employeeRepository->getEmployeeById($id);
        } catch (EmployeeNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find this Employee.']);
        }
        return $employee;
    }


    public function updateEmployee(int $id, array $requestPayload): Employee
    {
        $username = $requestPayload['username'];
        $facilityId = $requestPayload['facility_id'];

        try {
            $this->employeeRepository->getEmployeeById($id);
            $this->facilityRepository->getFacility($facilityId);
            $employee = $this->employeeRepository->updateEmployee($id, $username, $facilityId);
        } catch (EmployeeNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find this Employee.']);
        } catch (FacilityNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find this Facility.']);
        }
        return $employee;
    }

    public function deleteEmployee(int $id): Employee
    {
        try {
            $employee = $this->employeeRepository->getEmployeeById($id);
        } catch (EmployeeNotFoundException $exception){
            throw new NotFound(['message' => 'Unable to find this Employee.']);
        }
        $this->employeeRepository->deleteEmployee($id);
        return $employee;
    }



}