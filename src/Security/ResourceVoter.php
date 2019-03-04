<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ResourceVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if (!\is_array($subject) || !isset($subject['repository_name'])) {
            return;
        }

        return true;
    }
}
