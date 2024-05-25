<?php
namespace App\Model\Table;
use Cake\Validation\Validator;
 
use Cake\ORM\Table;
 
class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this ->addBehavior('Timestamp') ;
      }
}