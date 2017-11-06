<?php

namespace Abstem\Invites\Drivers;

use Abstem\Invites\Contracts\DriverContract;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class UuidDriver implements DriverContract
{
    /**
     * @return string
     */
    public function code(): string
    {
        $version = config('invites.uuid.version', 4);
        $method = 'createVersion' . $version . 'Uuid';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new InvalidArgumentException("Version [$version] not supported.");
    }

    /**
     * @return string
     */
    protected function createVersion1Uuid(): string
    {
        return Uuid::uuid1()->toString();
    }

    /**
     * @return string
     */
    protected function createVersion3Uuid(): string
    {
        $this->mayThrowError('3');

        return Uuid::uuid3(config('invites.uuid.namespace'), config('invites.uuid.name'))->toString();
    }

    /**
     * @return string
     */
    protected function createVersion4Uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    protected function createVersion5Uuid():string
    {
        $this->mayThrowError('5');

        return Uuid::uuid5(config('invites.uuid.namespace'), config('invites.uuid.name'))->toString();
    }

    private function mayThrowError(string $version = '3')
    {
        throw_unless(config('invites.uuid.namespace'), InvalidArgumentException::class, 'Namespace must be set for uuid version ' . $version);
        throw_unless(config('invites.uuid.name'), InvalidArgumentException::class, 'Name must be set for uuid version ' . $version);
    }
}

