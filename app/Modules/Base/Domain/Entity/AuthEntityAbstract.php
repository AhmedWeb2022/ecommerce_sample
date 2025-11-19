<?php

namespace App\Modules\Base\Domain\Entity;

use Illuminate\Database\Eloquent\Model;

abstract class AuthEntityAbstract
{

    // protected string $guard;
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $phone;
    public string $status;

    public Model $model;
    public function __construct(int $id, string $name, string $email, string $password, string $phone,string $status, Model $model, array $data = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->model = $model;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->status = $status;
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function getId()
    {
        // Get the ID of the AuthEntity
        return $this->id;
    }
    public function getName()
    {
        // Get the name of the AuthEntity
        return $this->name;
    }

    public function getModel(): Model
    {
        // Get the name of the AuthEntity
        return $this->model;
    }

    public function getAuthEntity()
    {
        // Get the AuthEntity instance
        return $this;
    }
}
