<?php
namespace Laravolt\Auth\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static UserStatus PENDING()
 * @method static UserStatus ACTIVE()
 * @method static UserStatus BLOCKED()
 */
class UserStatus extends Enum
{
    const PENDING = 'PENDING';
    const ACTIVE = 'ACTIVE';
    const BLOCKED = 'BLOCKED';
}
