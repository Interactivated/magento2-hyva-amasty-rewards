<?php

namespace Hyva\AmastyRewards\Api;

interface AmountManagementInterface
{
    /**
     * For cart page only.
     *
     * @param int $customerId
     * @param int $amount
     * @param int $remove
     *
     * @return string
     */
    public function usePoints($customerId, $amount = 0, $remove = 0);
}
