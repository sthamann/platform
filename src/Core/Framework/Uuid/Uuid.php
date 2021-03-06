<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Uuid;

use Shopware\Core\Framework\Uuid\Exception\InvalidUuidException;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException;

class Uuid
{
    /**
     * Regular expression pattern for matching a valid UUID of any variant.
     */
    public const VALID_PATTERN = '^[0-9A-Fa-f]{8}-?[0-9A-Fa-f]{4}-?[0-9A-Fa-f]{4}-?[0-9A-Fa-f]{4}-?[0-9A-Fa-f]{12}$';

    public static function randomHex(): string
    {
        $hex = bin2hex(random_bytes(16));
        $timeHi = self::applyVersion(substr($hex, 12, 4), 4);
        $clockSeqHi = self::applyVariant(hexdec(substr($hex, 16, 2)));

        return sprintf('%08s%04s%04s%02s%02s%012s',
            // time low
            substr($hex, 0, 8),
            // time mid
            substr($hex, 8, 4),
            // time high and version
            str_pad(dechex($timeHi), 4, '0', STR_PAD_LEFT),
            // clk_seq_hi_res
            str_pad(dechex($clockSeqHi), 2, '0', STR_PAD_LEFT),
            // clock_seq_low
            substr($hex, 18, 2),
            // node
            substr($hex, 20, 12)
        );
    }

    public static function randomBytes(): string
    {
        return hex2bin(self::randomHex());
    }

    /**
     * @throws InvalidUuidException
     * @throws InvalidUuidLengthException
     */
    public static function fromBytesToHex(string $bytes): string
    {
        if (\strlen($bytes) !== 16) {
            throw new InvalidUuidLengthException(\strlen($bytes), bin2hex($bytes));
        }
        $uuid = bin2hex($bytes);

        if (!self::isValid($uuid)) {
            throw new InvalidUuidException($uuid);
        }

        return $uuid;
    }

    /**
     * @throws InvalidUuidException
     */
    public static function fromHexToBytes(string $uuid): string
    {
        $uuid = strtolower($uuid);
        if ($bin = @hex2bin(str_replace('-', '', $uuid))) {
            return $bin;
        }

        throw new InvalidUuidException($uuid);
    }

    public static function isValid(string $id): bool
    {
        if (!preg_match('/' . self::VALID_PATTERN . '/', $id)) {
            return false;
        }

        return true;
    }

    private static function applyVersion(string $timeHi, int $version): int
    {
        $timeHi = hexdec($timeHi) & 0x0fff;
        $timeHi &= ~(0xf000);
        $timeHi |= $version << 12;

        return $timeHi;
    }

    private static function applyVariant(int $clockSeqHi): int
    {
        // Set the variant to RFC 4122
        $clockSeqHi = $clockSeqHi & 0x3f;
        $clockSeqHi &= ~(0xc0);
        $clockSeqHi |= 0x80;

        return $clockSeqHi;
    }
}
