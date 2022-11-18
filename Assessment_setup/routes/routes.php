<?php
// Authentication
$router->post('/api/auth',App\Controllers\AuthenticationController::class . '@authentication');

// Tags
$router->get('/api/tags',App\Controllers\TagController::class . '@listTags');
$router->post('/api/tags',App\Controllers\TagController::class . '@createTag');

// Facilities
$router->get('/api/facilities',App\Controllers\FacilityController::class . '@listFacilities');
$router->post('/api/facilities',App\Controllers\FacilityController::class . '@createFacility');
$router->get('/api/facilities/{id}',\App\Controllers\FacilityController::class . '@getFacility');
$router->delete('/api/facilities/{id}/tags/{tagId}',App\Controllers\FacilityController::class . '@deleteTag');
$router->post('/api/facilities/{id}/tags',App\Controllers\FacilityController::class . '@addTags');
$router->post('/api/facilities/{id}',\App\Controllers\FacilityController::class . '@updateFacility');
$router->delete('/api/facilities/{id}',App\Controllers\FacilityController::class . '@deleteFacility');


// Employees
$router->get('/api/employees',App\Controllers\EmployeeController::class . '@listEmployees');
$router->post('/api/employees',App\Controllers\EmployeeController::class . '@createEmployee');
$router->get('/api/employees/{id}',App\Controllers\EmployeeController::class . '@getEmployee');
$router->post('/api/employees/{id}',App\Controllers\EmployeeController::class . '@updateEmployee');
$router->delete('/api/employees/{id}',App\Controllers\EmployeeController::class . '@deleteEmployee');
