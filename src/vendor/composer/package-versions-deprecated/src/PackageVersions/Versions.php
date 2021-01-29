<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = '__root__';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6',
  'doctrine/annotations' => '1.11.1@ce77a7ba1770462cd705a91a151b6c3746f9c6ad',
  'doctrine/cache' => '1.10.2@13e3381b25847283a91948d04640543941309727',
  'doctrine/collections' => '1.6.7@55f8b799269a1a472457bd1a41b4f379d4cfba4a',
  'doctrine/common' => '3.1.0@9f3e3f3cc5399604c0325d5ffa92609d694d950d',
  'doctrine/dbal' => '2.12.1@adce7a954a1c2f14f85e94aed90c8489af204086',
  'doctrine/event-manager' => '1.1.1@41370af6a30faa9dc0368c4a6814d596e81aba7f',
  'doctrine/inflector' => '2.0.3@9cf661f4eb38f7c881cac67c75ea9b00bf97b210',
  'doctrine/instantiator' => '1.4.0@d56bf6102915de5702778fe20f2de3b2fe570b5b',
  'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042',
  'doctrine/orm' => '2.8.1@242cf1a33df1b8bc5e1b86c3ebd01db07851c833',
  'doctrine/persistence' => '2.1.0@9899c16934053880876b920a3b8b02ed2337ac1d',
  'firebase/php-jwt' => 'v5.2.0@feb0e820b8436873675fd3aca04f3728eb2185cb',
  'monolog/monolog' => '2.1.1@f9eee5cec93dfb313a38b6b288741e84e53f02d5',
  'nikic/fast-route' => 'v1.3.0@181d480e08d9476e61381e04a71b34dc0432e812',
  'oscarotero/psr7-middlewares' => 'v3.21.1@639c87ddf9647224b9d3f93899276cc25a7a1aa9',
  'pimple/pimple' => 'v3.3.1@21e45061c3429b1e06233475cc0e1f6fc774d5b0',
  'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
  'psr/http-factory' => '1.0.1@12ac7fcd07e5b077433f5f2bee95b3a771bf61be',
  'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363',
  'psr/http-server-handler' => '1.0.1@aff2f80e33b7f026ec96bb42f63242dc50ffcae7',
  'psr/http-server-middleware' => '1.0.1@2296f45510945530b9dceb8bcedb5cb84d40c5f5',
  'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc',
  'robmorgan/phinx' => 'v0.5.5@da9950a5e5b9314ebf72ddc6317ed587ce9ba8dc',
  'slim/php-view' => '2.2.1@a13ada9d7962ca1b48799c0d9ffbca4c33245aed',
  'slim/slim' => '3.12.3@1c9318a84ffb890900901136d620b4f03a59da38',
  'symfony/config' => 'v3.4.47@bc6b3fd3930d4b53a60b42fe2ed6fc466b75f03f',
  'symfony/console' => 'v3.4.47@a10b1da6fc93080c180bba7219b5ff5b7518fe81',
  'symfony/debug' => 'v4.4.17@65fe7b49868378319b82da3035fb30801b931c47',
  'symfony/filesystem' => 'v4.4.17@17b83e36a911aefa2cfe04bbf6328ec4c040c1b2',
  'symfony/polyfill-ctype' => 'v1.20.0@f4ba089a5b6366e453971d3aad5fe8e897b37f41',
  'symfony/polyfill-mbstring' => 'v1.20.0@39d483bdf39be819deabf04ec872eb0b2410b531',
  'symfony/polyfill-php80' => 'v1.20.0@e70aa8b064c5b72d3df2abd5ab1e90464ad009de',
  'symfony/yaml' => 'v3.4.47@88289caa3c166321883f67fe5130188ebbb47094',
  'tuupola/callable-handler' => '1.1.0@0bc7b88630ca753de9aba8f411046856f5ca6f8c',
  'tuupola/http-factory' => '1.3.0@aa48841a9f572b9cebe9d3ac5d5d3362a83f57ac',
  'tuupola/slim-basic-auth' => '3.3.1@18e49c18f5648b05bb6169d166ccb6f797f0fbc4',
  'tuupola/slim-jwt-auth' => '3.5.1@1c3668deebbcbdc49c9abe6cce4f6e0c8c5e09b5',
  '__root__' => 'dev-master@b01d327408ca9acac678079e4fbde5be5df0f794',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!class_exists(InstalledVersions::class, false) || !InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (class_exists(InstalledVersions::class, false) && InstalledVersions::getRawData()) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }
}
