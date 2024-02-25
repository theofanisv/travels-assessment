<?php

namespace App\Http\Requests\Travel;

trait HandlesTravelMedia
{
    protected function handleMedia(): static
    {
        if ($this->file('thumbnail')) {
            $this->travel->addMediaFromRequest('thumbnail')
                ->toMediaCollection('thumbnail');
        }

        if ($this->file('photos')) {
            $this->travel->addMultipleMediaFromRequest(['photos'])
                ->each(fn($fileAdder) => $fileAdder->toMediaCollection('photos'));
        }
        
        return $this;
    }
    
}