<?php

namespace App\Models\Observers;

use App\Models\Organization;

/**
 * Handles lifecycle events for the Organization model.
 */
class OrganizationObserver
{
    const SEP = ':';

    /**
     * Handle the Organization "creating" event.
     *
     * @param Organization $organization The organization instance being created.
     */
    public function creating(Organization $organization): void
    {
        $organization->node_path = '';
    }

    /**
     * Handle the Organization "created" event.
     *
     * @param Organization $organization The organization instance that was created.
     */
    public function created(Organization $organization): void
    {
        $this->assignNodePath($organization);

        $organization->saveQuietly();
    }

    /**
     * Handle the Organization "updating" event.
     *
     * @param Organization $organization The organization instance being updated.
     */
    public function updating(Organization $organization): void
    {
        $this->assignNodePath($organization);
    }

    /**
     * Generates and assigns a materialized path for the organization.
     *
     * This path represents the hierarchy of the organization by concatenating the IDs of its ancestors.
     *
     * @param Organization $organization The organization to generate the path for.
     */
    private function assignNodePath(Organization $organization): void
    {
        $node_path = [$organization->id];

        $node = $organization;

        while ($node = $node->parent) // assumes you defined a parent() relationship
        {
            // prepend each ancestor slug
            $node_path[] = $node->id;
        }

        // Reverse so root comes first, then join with dots
        $organization->node_path = implode(self::SEP, array_reverse($node_path)) . self::SEP;
    }
}
