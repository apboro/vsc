<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;

class ForOrganization
{
    /** @var int|null */
    protected ?int $organizationId;

    /** @var bool */
    protected bool $withUnBound;

    /**
     * @param int|null $organizationId
     * @param bool $withUnBound
     */
    public function __construct(?int $organizationId, bool $withUnBound = false)
    {
        $this->organizationId = $organizationId;
        $this->withUnBound = $withUnBound;
    }

    /**
     * @param Builder $query
     *
     * @return void
     *
     * @see IncomingDocument
     */
    public function __invoke(Builder $query): void
    {
        // prevent if organization ID not set
        if ($this->organizationId === null && !$this->withUnBound) {
            $query->where('organization_id', 0);
            return;
        }

        $query->where(function (Builder $query) {
            $query->where('organization_id', $this->organizationId);
            if ($this->withUnBound) {
                $query->orWhereNull('organization_id');
            }
        });
    }
}
