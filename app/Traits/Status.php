<?php

namespace App\Traits;

/**
 * Class Status
 * @package App
 * 
 * @property string $status
 */
trait Status
{
    /**
     * set status enable
     */
    public function setStatusEnable()
    {
        $this->status = 'enable';
    }

    /**
     * set status disable
     */
    public function setStatusDisable()
    {
        $this->status = 'disable';
    }

    /**
     * set status deleted
     */
    public function setStatusDeleted()
    {
        $this->status = 'deleted';
    }

    /**
     * set status locked
     */
    public function setStatusLocked()
    {
        $this->status = 'locked';
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->status === $this->getStatusEnable();
    }

    /**
     * @return bool
     */
    public function isDisable()
    {
        return $this->status === $this->getStatusDisable();
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->status === $this->getStatusDeleted();
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->status === $this->getStatusLocked();
    }

    /**
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    public function getStatusText()
    {
        return trans('common.status.' . $this->status);
    }

    /**
     * @return string
     */
    public function getStatusEnable()
    {
        return 'enable';
    }

    /**
     * @return string
     */
    public function getStatusDisable()
    {
        return 'disable';
    }

    /**
     * @return string
     */
    public function getStatusLocked()
    {
        return 'locked';
    }

    /**
     * @return string
     */
    public function getStatusDeleted()
    {
        return 'deleted';
    }
}