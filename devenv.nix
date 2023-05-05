{ pkgs, lib, config, ... }:

{
  packages = [
    pkgs.gnupatch
    pkgs.nodePackages_latest.yalc
    pkgs.gnused
    pkgs.symfony-cli
    pkgs.deno
  ];

  languages.php = {
    enable = lib.mkDefault true;
    version = lib.mkDefault "8.2";
    extensions = [ "grpc" "xdebug" ];

    ini = ''
      memory_limit = 2G
      realpath_cache_ttl = 3600
      session.gc_probability = 0
      display_errors = On
      error_reporting = E_ALL
      assert.active = 0
      opcache.memory_consumption = 256M
      opcache.interned_strings_buffer = 20
      zend.assertions = 0
      short_open_tag = 0
      zend.detect_unicode = 0
      realpath_cache_ttl = 3600
      xdebug.mode = debug
      xdebug.discover_client_host = 1
      xdebug.start_with_request=yes
    '';
  };

  env.APP_SECRET = lib.mkDefault "devsecret";
}
