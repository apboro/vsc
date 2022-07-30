<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;

class ForOrganization
{
    /** @var int|null */
    protected ?int $organizationId;

    /**
     * @param int|null $organizationId
     */
    public function __construct(?int $organizationId)
    {
        $this->organizationId = $organizationId;
    }

    /**
     * @param Builder $query
     *
     * @return void
     *
     * @see IncomingDocument
     */
    public function __invoke(Builder $query)
    {
        // prevent if organization ID not set
        if ($this->organizationId === null) {
            $query->where('id', 0);
            return;
        }

        $query->where('organization_id', $this->organizationId);
    }
}
