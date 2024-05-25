<?php
// src/Controller/PagesController.php

namespace App\Controller;

use App\Controller\AppController;
use App\Controller\NotFoundException;

class UsersController extends AppController
{
    public function home()
    {
        // Your display logic here
    }

    // ----------------------------------SignUp--------------------------------

    public function signup()
    {
        if ($this->request->is('post')) {

            $name = !empty($this->request->getData('name')) ? trim($this->request->getData('name')) : '';
            $username = !empty($this->request->getData('username')) ? trim($this->request->getData('username')) : '';
            $password = !empty($this->request->getData('password')) ? trim($this->request->getData('password')) : '';
            $confirmpassword = !empty($this->request->getData('confirmpassword')) ? trim($this->request->getData('confirmpassword')) : '';

            $errors = [];

            if (empty($name)) {
                $errors[] = "Name is blank";
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $errors[] = "Special characters not allowed in name!";
            } else if (!strlen($name) >= 10 && strlen($name) <= 60) {
                $errors[] = "Name should be at least 10 characters long and not be more than 60 characters long!";
            } else if(!preg_match('/^[^\s]+(\s[^\s]+)*$/', $name)){
                $errors[] = 'Name should have only one space between words';
            }

            if (empty($username)) {
                $errors[] = "username is blank";
            } else if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $username)) {
                $errors[] = "Only alphabets and numbers allowed in user name, Special characters are not allowed!";
            } else if (strrpos($username, ' ') !== false) {
                $errors[] = "Space not allowed in username";
            } else if (strlen($username) <= 8) {
                $errors[] = "Your UserName must contain atleast 8 Characters!";
            }

            if (empty($password)) {
                $errors[] = "Please enter your password. This field cannot be left blank";
            } elseif (!preg_match("/^[a-zA-Z0-9-' ]*$/", $password)) {
                $errors[] = "Special characters not allowed in password!";
            } elseif (strlen($password) < 8) {  // Corrected this line
                $errors[] = "Your Password Must Contain At Least 8 Characters!";
            } elseif (strpos($password, ' ') !== false) {
                $errors[] = "Your Password Should Not Contain Spaces!";
            } elseif (!preg_match('/^[^\s]+(\s[^\s]+)*$/', $password)) {
                $errors[] = 'Password should have only one space between words';
            }

            if (empty($confirmpassword)) {
                $errors[] = "Please confirm your password. This field cannot be left blank";
            }

            if (!empty($password) && !empty($confirmpassword) && $password != $confirmpassword) {
                $errors[] = "password and confirm password is not matched";
            }
            $this->loadModel('users');

            if (empty($errors)) {

                $checkUsernameExists = $this->users->find('all', [
                    'conditions' => [
                        'users.username' => $username
                    ]

                ])->first();
                if (empty($checkUsernameExists)) {
                    $users_table = array();
                    $users_table['name'] = $name;
                    $users_table['username'] = $username;
                    $users_table['password'] = $password;
                    $users_table['status'] = 0;

                    $abc = $this->users->newEntity($users_table);
                    if ($this->users->save($abc)) {
                        $response = [
                            'success' => true,
                            'message' => 'Signup successful'
                        ];
                    } else {
                        $errors[] = "somthing went wrong";
                    }
                } else {
                    $errors[] = "Same username => $username is already exists.";
                }
            }
            if (!empty($errors)) {
                $response = [
                    'success' => false,
                    'message' => 'Errors',
                    'error' => $errors
                ];
            }
            echo json_encode($response);
            die;
        }
    }

    // ----------------------------------Login--------------------------------
    public function login()
    {
        $session = $this->request->getSession();
        if (!empty($session->read('username'))) {
            // If logged in, redirect to the dashboard or any other desired page
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        if ($this->request->is('post')) {
            $username = $this->request->getData('username');
            $password = $this->request->getData('password');


            $admin = $this->Users->find()->where(['username' => $username, 'password' => $password, 'role' => '1', 'status' => '1'])->first();
            $guest = $this->Users->find()->where(['username' => $username, 'password' => $password, 'role' => '0', 'status' => '1'])->first();

            if ($admin) {
                $adminId = $admin->id;
                $session = $this->request->getSession();
                $session->write('username', $username);
                $session->write('role', 'Admin');
                $session->write('adminId', $adminId);

                //$this->Flash->success('Login successful By Admin!');
                echo 'success';
                die;
                //return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);

            } else if ($guest) {
                $guestId = $guest->id;
                $session = $this->request->getSession();
                $session->write('username', $username);
                $session->write('role', 'Guest');
                $session->write('guest', $guestId);
                echo 'success';
                die;
                

            } else {
                //$this->Flash->error('Invalid username or password. Please try again.');
                //return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                echo 'Invalid username or password. Please try again.';
                die;
            }
        }
    }

    // ----------------------------------dashboard--------------------------------
    public function dashboard()
    {

        //set session
        $session = $this->request->getSession();
        $username =  $session->read('username');

        if (empty($username)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        } 
        $query = $this->Users->find();
        $this->set('results', $query);
    }

    // ----------------------------------userlist--------------------------------
    public function userlist()
    {
        $session = $this->request->getSession();
        $username =  $session->read('username');
        if (empty($username)) {
            $this->Flash->success('Please Login first');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
        $query = $this->Users->find();
        $resultArray = array();
        // $status = array();
        foreach ($query as $value) {
            if ($value->status == "0") {
                $value->status = "inactive";
                array_push($resultArray, $value);
            } else {
                $value->status = "active";
                array_push($resultArray, $value);
            }
        }
        $this->set('results', $resultArray);
    }

    // ----------------------------------adduser--------------------------------
   

    public function adduser()
    {
        if ($this->request->is('post')) {
            $name = !empty($this->request->getData('name')) ? trim($this->request->getData('name')) : '';
            $username = !empty($this->request->getData('username')) ? trim($this->request->getData('username')) : '';
            $password = !empty($this->request->getData('password')) ? trim($this->request->getData('password')) : '';
            $confirmpassword = !empty($this->request->getData('confirmpassword')) ? trim($this->request->getData('confirmpassword')) : '';
 
            $errors = [];
 
            if (empty($name)) {
                $errors[] = "Name is blank";
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $errors[] = "Special characters not allowed in name!";
            } else if (!strlen($name) >= 10 && strlen($name) <= 60) {
                $errors[] = "Name should be at least 10 characters long and not be more than 60 characters long!";
            } else if(!preg_match('/^[^\s]+(\s[^\s]+)*$/', $name)){
                $errors[] = 'Name should have only one space between words';
            }

            if (empty($username)) {
                $errors[] = "username is blank";
            } else if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $username)) {
                $errors[] = "Only alphabets and numbers allowed in user name, Special characters are not allowed!";
            } else if (strrpos($username, ' ') !== false) {
                $errors[] = "Space not allowed in username";
            } else if (strlen($username) < 8) {
                $errors[] = "Your UserName must contain atleast 8 Characters!";
            }

            if (empty($password)) {
                $errors[] = "Please enter your password. This field cannot be left blank";
            } elseif (!preg_match("/^[a-zA-Z0-9-' ]*$/", $password)) {
                $errors[] = "Special characters not allowed in password!";
            } elseif (strlen($password) < 8) {  // Corrected this line
                $errors[] = "Your Password Must Contain At Least 8 Characters!";
            } elseif (strpos($password, ' ') !== false) {
                $errors[] = "Your Password Should Not Contain Spaces!";
            } elseif (!preg_match('/^[^\s]+(\s[^\s]+)*$/', $password)) {
                $errors[] = 'Password should have only one space between words';
            }

            if (empty($confirmpassword)) {
                $errors[] = "Please confirm your password. This field cannot be left blank";
            }

            if (!empty($password) && !empty($confirmpassword) && $password != $confirmpassword) {
                $errors[] = "password and confirm password is not matched";
            }

            $this->loadModel('users');
 
            if (empty($errors)) {
 
                $checkUsernameExists = $this->users->find('all', [
                    'conditions' => [
                        'users.username' => $username
                    ]
                ])->first();
                if (empty($checkUsernameExists)) {
                    $users_table = array();
                    $users_table['name'] = $name;
                    $users_table['username'] = $username;
                    $users_table['password'] = $password;
                    // $users_table['status'] = $status;
 
 
 
                    $statusFromForm = $this->request->getData('status');
                    // echo $statusFromForm;die;
                    if ($statusFromForm !== null && $statusFromForm == 'active') {
 
                        $users_table['status'] = 1;
                    } else {
                        // Set a default value if 'status' is not set in the form data
                        $users_table['status'] = 0;
                    }
                    // print_r($users_table);die;
                    $abc = $this->users->newEntity($users_table);
                    if ($this->users->save($abc)) {
                        $response = [
                            'success' => true,
                            'message' => 'Add successful'
                        ];
                    } else {
                        $errors[] = "somthing went wrong";
                    }
                } else {
                    $errors[] = "Same username => $username is already exists.";
                }
            }
            if (!empty($errors)) {
                $response = [
                    'success' => false,
                    'message' => 'Errors',
                    'error' => $errors
                ];
            }
            echo json_encode($response);
            die;
        }
    }

    //---------------------------Status--------------------------

    public function status($id)
    {
        $this->autoRender = false; // Disable the automatic rendering of views

        if (!empty($id)) {
            $user = $this->Users->get($id);
            $status = ($user->status == '0') ? '1' : '0';   
            $userfix = $this->Users->patchEntity($user, ['status' => $status]);
            
            //$response = ['success' => false, 'message' => 'Failed to change user status.'];

            if ($this->Users->save($userfix)) {
                //$response = ['success' => true, 'status' => $status];
                echo 'success';
                die;
            } else {
                //$response = ['success' => false, 'meu   ssage' => 'Failed to save user status.'];
                echo 'Failed to save user status.';
                die;
            }
            // Convert the response array to JSON and send it
            //echo json_encode($response);
        }
    }

    //---------------------------edit--------------------------

    public function edit($id = null)
    {
        $session = $this->request->getSession();
        $role= $session->read('role');
        $username =  $session->read('username');
        
        if(empty($username)){
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
     
        if($role != 'Admin')
        {
            $session->delete('username');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['post', 'put'])) {
            $requestData = $this->request->getData();

            // validation for name
            if (empty($requestData['name'])){
                $this->set([
                    'status' => 'error',
                    'message' => 'Name connat be blank.',
                ]);
            } elseif (preg_match('/\s{2,}/', $requestData['name'])){
                $this->set([
                    'status' => 'error',
                    'message' => 'Only one space is allowed between the characters.',
                ]);
            } elseif (preg_match('/[^a-zA-Z\s]/', $requestData['name'])){
                $this->set([
                    'status' => 'error',
                    'message' => 'Name cannot contain special characters or numbers.',
                ]);
            } else {
                $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->set([
                        'status' => 'success',
                        'message' => 'User has been updated',
                    ]);
                } else {
                    $this->set([
                        'status' => 'error',
                        'message' => 'Unable to update the user. Please, try again.',
                    ]);
                }
            }
            $this->viewBuilder()->setOption('serialize', ['status', 'message', 'errors']);
            return;
        }
        $this->set(compact('user'));
    }
    //-----------------------------------change Password-----------------------

    // UsersController.php
    public function changePassword($id)
    {
        // set session
        $session = $this->request->getSession();
        $userId = $session->read('user_id');
     
        if (empty($userId)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
     
        $error = 0;
     
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $currentPassword = $data['current_password'];
            $newPassword = $data['new_password'];
            $confirmPassword = $data['confirm_password'];
     
            // Current Password validation
            if (empty($currentPassword)) {
                $this->Flash->error('Please enter the Current Password');
                $error = 1;
            } else {
                // Check if the current password matches the password in the database
                $user = $this->Users->find()
                    ->where(['id' => $userId, 'password' => $currentPassword])
                    ->first();
     
                if (!$user && password_verify($currentPassword, $user->password)) {
                    $this->Flash->error('Current Password is incorrect');
                    $error = 1;
                } else{
                    $this->Flash->error('Current Password is incorrect');
                    $error = 1;
                }
            }
     
            // New Password Validation
            if (empty($newPassword)) {
                $this->Flash->error('Please enter the New Password');
                $error = 1;
            } else if (!preg_match("/^[a-zA-Z0-9]{8}$/", $newPassword)) {
                $this->Flash->error('Password must be at least 8 characters and include both letters and numbers');
            }
     
            // Check if passwords match
            if ($newPassword != $confirmPassword) {
                $this->Flash->error('Passwords do not match. Please try again.');
                $error = 1;
            }
     
            if ($error == 0) {
                $user = $this->Users->get($userId);
     
                // Hash and set the password
                $user->password = $newPassword;
     
                if ($this->Users->save($user)) {
                    $this->Flash->success('Password updated successfully.');
                    return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
                } else {
                    $this->Flash->error('Unable to update password. Please try again.');
                }
            }
        }
     
        $this->set(compact('userId'));
    }


    // ----------------------------------update--------------------------------
    public function update($id = null)
    {
        //set session
        $session = $this->request->getSession();
        $username =  $session->read('username');
        if (empty($username)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    
        $error = 0;
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');
            $data = $this->request->getData();
            $currentpassword = $this->request->getData('current_password');
            $newpassword = $this->request->getData('new_password');
            $confirmpassword = $this->request->getData('confirm_password');

            // Current Password validation
            if (empty($data['current_password'])) {
                $this->Flash->error('Please enter the Current Password');
                $error = 1;
            } else {
                // Check if the current password matches the password in the database
                $this->loadModel('Users');
                $user = $this->Users->find()
                    ->where(['id' => $id, 'password' => $currentpassword])
                    ->first();
    
                if (!$user) {
                    $this->Flash->error('Current Password is incorrect');
                    $error = 1;
                }
            }

            //New Password Validation
            if (empty($data['new_password'])) {
                $this->Flash->error('Please enter the New Password');
                $error = 1;
            } else if (!preg_match("/^[a-zA-Z0-9]{8}$/", $data['new_password'])) {
                $this->Flash->error('Password must be at least 8 characters and include both letters and numbers');
            } else {
                // Check if passwords match
                if ($data['new_password'] != $data['confirm_password']) {
                    $this->Flash->error('Passwords do not match. Please try again.');
                    $error = 1;
                } else {
                    // Hash and set the password
                    //$user->password = $data['confirmpassword'];
                    //$user->password = (new DefaultPasswordHasher())->hash($data['password']);
                }
            }
            if ($error == 0) {
                $this->loadModel('users');
                $users = $this->Users->get($id);

                $data = array();
                $data['password'] = $confirmpassword;

                $entity = $this->Users->patchEntity($users, $data);
                $this->Users->save($entity);
                $this->Flash->success('Password updated successfully.');
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            }
        }
        $this->set(compact('id'));
    }



    //---------------------------delete--------------------------

    public function delete($id)
    {
        $user =  $this->Users->get($id);
        if ($this->Users->delete($user)) {
            //$this->Flash->success('User Deleted successfully');
            echo 'success';
            die;
        }
    }


    //---------------------------logout--------------------------
    public function logout()
    {
        $session = $this->request->getSession();
        $session->delete('username');
        return $this->redirect(['controller' => 'Users', 'action' => 'home']);
    }
}