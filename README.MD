## About

Lumenous.org is a 100% open source, fully functioning Stellar Inflation Pool with a focus on charitable contributions.

This repository contains the entirety of code necessary for running and maintaining your own Stellar Inflation pool. It is a mirror of the code
which runs on Lumenous.org.

Unique aspects of this application are it's ability to:

* Allow individual users to specify a percentage to contribute to charity
* Allow user authentication tied to a public key by having a user verify they own the public key by performing a "Manage Data" operation
* Track members of the pool and their individual holdings
* Historically track when the pool account is credited
* Historically track when individual payouts are made
* Historically track when donations are made
* Manage charitable organizations and their accompanying public keys via an administrative dashboard

Lumenous.org was built using the following modern technologies:

* Laravel 5.5
* PHP 7+
* MySQL/MariaDB
* vue.js
* Bootstrap 3

## Configuration

* Standard Laravel 5.5 application setup
* Copy the `.env.example` file to `.env` and populate with your own values
* Server must be configured with supervisord to handle Laravel worker queues
* Run initial database migrations via `php artisan migrate` to setup the core database tables
* To rebuild frontend styles and scripts, use `npm run dev` and `npm run production`

## Credits

* [CoPilot](https://github.com/misterGF/CoPilot), a responsive Bootstrap 3 admin template based on AdminLTE using vue.js
* [zulucrypto/stellar-api](https://github.com/zulucrypto/stellar-api), the first PHP SDK for Stellar

## Future Ideas

* Improved UI
* Database backups to Amazon S3/Glacier
* Easier integration of multisig for initiating pool payouts (in progress on branch ``)

## Other Pools

* https://github.com/Lumenaut-Network/pool
* https://galactictalk.org/d/766-moonpool-stellar-inflation-pool-with-0-fees

## Key Stellar SDK Concepts Used

* Tracking Changes to Inflation Destination
  * 
* Manage Data
  * https://www.stellar.org/developers/guides/concepts/list-of-operations.html#manage-data
* Watch for INFLATION operation via Horizon streaming payment events
  * https://www.stellar.org/developers/horizon/learn/responses.html#streaming
  * https://www.stellar.org/developers/horizon/reference/resources/operation.html#inflation
* Check subsequent Effects of INFLATION transaction 
  * https://www.stellar.org/developers/horizon/reference/resources/effect.html
  * https://horizon-testnet.stellar.org/operations/28447127474540545/effects

## Displaying Crypto Values

https://scotch.io/tutorials/build-a-cryptocurrency-comparison-site-with-vuejs

## Charities Considered

The following charities will be considered for Lumenous based on their global impact and Charity Navigator score.

* Direct Relief
* WE Charity
* Water Mission
* MAP International
* Environmental Defense Fund
* Books for Africa
* Against Malaria Foundation

## License

The project is GPLv2 licensed, and we strongly encourage contributions.
