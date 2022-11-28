<?php

namespace App\Repositories;

use App\Entities\Employee;
use App\Exceptions\EmployeeAlreadyExistsException;
use App\Exceptions\EmployeeNotFoundException;
use App\Plugins\Db\Db;

class EmployeeRepository
{
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }


    public function getEmployeeByUsername(string $username): Employee
    {
        $sqlGetEmployee = '
            SELECT * FROM employee AS emp
            WHERE emp.username = :username';

        $this->db->executeQuery($sqlGetEmployee, [
            'username' => $username
        ]);

        $result = $this->db->getStatement()->fetch();
        if (empty($result)){
            throw new EmployeeNotFoundException();
        }
        return new Employee(
            $result['id'],
            $username,
            $result['password'],
            $result['facility_id']
        );
    }


    public function getEmployeeById(int $id): Employee
    {
        $sqlGetEmployee = '
            SELECT * FROM employee AS emp
            WHERE emp.id = :id';

        $this->db->executeQuery($sqlGetEmployee, [
            'id' => $id
        ]);

        $result = $this->db->getStatement()->fetch();
        if (empty($result)){
            throw new EmployeeNotFoundException();
        }
        return new Employee(
            $result['id'],
            $result['username'],
            $result['password'],
            $result['facility_id']
        );
    }


    public function createEmployee(string $username, string $password, int $facilityId): Employee
    {
        $sqlCreateEmployee = '
            INSERT INTO employee (id, username, password, facility_id) 
            VALUES (
                    NULL, 
                    :username, 
                    :password, 
                    :facilityId)';

        $this->db->executeQuery($sqlCreateEmployee, [
            'username' => $username,
            'password' => $password,
            'facilityId' => $facilityId
        ]);
        $employeeId = $this->db->getLastInsertedId();
        if ($employeeId==0){
            throw new EmployeeAlreadyExistsException();
        }
        return new Employee(
            $employeeId,
            $username,
            $password,
            $facilityId
        );
    }


    public function listEmployees(): array
    {
        $sqlListEmployees = '
            SELECT * FROM employee';

        $this->db->executeQuery($sqlListEmployees);
        $results = $this->db->getStatement()->fetchAll();
        if (empty($results)){
            throw new EmployeeNotFoundException();
        }
        $employees = [];
        foreach ($results as $result){
            $employees[] = new Employee(
                $result['id'],
                $result['username'],
                $result['password'],
                $result['facility_id']
            );
        }
        return $employees;
    }

    public function updateEmployee(int $id, string $username, int $facilityId): Employee
    {
        $sqlUpdateEmployee = '
            UPDATE employee
            SET username= :username, facility_id = :facilityId
            WHERE id = :id';

        $result = $this->db->executeQuery($sqlUpdateEmployee, [
            'username' => $username,
            'facilityId' => $facilityId,
            'id' => $id
        ]);
        if ($result != 1){
            throw new EmployeeNotFoundException();
        }
        return new Employee(
            $id,
            $username,
            '',
            $facilityId
        );
    }

    public function deleteEmployee(int $id)
    {
        $sqlDeleteEmployee = '
            DELETE FROM employee
            WHERE id = :id';

        $this->db->executeQuery($sqlDeleteEmployee, ['id'=>$id]);
    }



}