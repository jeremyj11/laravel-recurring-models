<?php

namespace MohammedManssour\LaravelRecurringModels\Support\PendingRepeats;

use Carbon\Carbon;
use MohammedManssour\LaravelRecurringModels\Contracts\Repeatable;
use MohammedManssour\LaravelRecurringModels\Support\RepeatCollection;

abstract class PendingRepeat
{
    public Repeatable $model;

    public Carbon $start_at;

    public int $interval;

    public ?Carbon $end_at = null;

    public function __construct(Repeatable $model)
    {
        $this->model = $model;
    }

    public function endsAt(Carbon $end_at): static
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function startsAt(Carbon $start_at): static
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function repeatitions(): RepeatCollection
    {
        return $this->model->repetitions()->makeMany($this->rules());
    }

    public function save()
    {
        return $this->repeatitions()->save();
    }

    public function __destruct()
    {
        return $this->save();
    }

    /**
     * calculate the end_at based on # of times wanted
     */
    abstract public function endsAfter(int $times): static;

    /**
     * translates repeat requirements to repeat settings array
     */
    abstract public function rules(): array;
}
