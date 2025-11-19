<?php

namespace App\Modules\Auth\Domain\Entity;

class CustomerEntity
{
    public function __construct(
        public ?int $id,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $email,
        public ?string $phone,
        public bool $isBlocked = false,
        public bool $isVerified = false,
        public bool $isOnline = false,
        public bool $isApproved = false,
        public ?string $gender = null,
        public ?string $image = null,
        public ?string $cover = null,
        public ?string $fcmToken = null,
        public ?string $password = null,
        public ?string $verificationCode = null,
        public ?\DateTime $emailVerifiedAt = null,
        public ?\DateTime $verifiedAt = null,
    ) {}

        public function getId()
    {
        // Get the ID of the user
        return   $this->id;
    }
    public function getName()
    {
        // Get the name of the user
        return  $this->firstName . ' ' . $this->lastName;
    }
}
