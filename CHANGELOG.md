# Changelog
## 4.1.7
- Add SSO on 2FA field to the institution configuration

## 4.1.5
- Add SendSecondFactorRegistrationEmailCommand #96

## 4.1.4
- Support for self-asserted token registration

## 4.1.0..4.1.3
- NO changelog entries for these releases. Please consult the Github PR's for details

## 4.1.4
* Support for self-asserted token registration

## 3.0.20
* Provide support for self vetting

## 3.0.19
* Remove implicit raa from profile #86

## 3.0.18
* The actorInstitution was removed because the SRAA switcher is no longer useful after the FGA changes. 
This because earlier, all overviews were from the view point of an institution but now it's from a registration authority. #85

## 3.0.17
* Add profile helper function #84

## 3.0.16
* Expose the self service verified endpoint #82
* Updated the Symfony version constraints #83

## 3.0.15
Add implicit management roles to profile response #81

## 3.0.13 & 3.0.14
Add filter options to pagination results

## 3.0.12
In order to use the filters on the ra listing and ra candidate page
we need to return the possible available filters.

## 3.0.7
Add actorId to ra-listing endpoint
When requesting a single ra listing the actorId was not send so
a ra could be returned while this shouldn't be allowed.

## 3.0.6
Allow searching RA listing on name, email, role #78

## 3.0.5
New feature
* Add actorId to management

## 3.0.4
## 3.0.3
New feature
* Fix token listing for SRAA

## 3.0.2
New feature
Add possible institutions to candidate listing endpoint and not the unique identities to support fga

## 3.0.1
New feature
* Consume the profile endpoint #73   

## 3.0.0 

**FGA (fine grained authorization)**

The new fine grained authorization logic will allow RA's from other institutions to accredit RA's on behalf of another 
organisation. This is determined based on the institution configuration. 

https://github.com/OpenConext/Stepup-Deploy/wiki/rfc-fine-grained-authorization/b6852587baee698cccae7ebc922f29552420a296

**Other optimizations**
 * Security updates #62 #63 thanks @backendtea!

## 2.4.4
Removed RMT

## 2.4.3
Make services public to prevent Symfony deprecation warnings, will be undone with #138225085

## 2.4.2
Require the new release of the StepupBundle to be fully 3.4 compatible.

## 2.4.1
Add support for newer than v2.7 Symfony versions, they still have to be lower than version 4 though.

## 2.4.0
Add support for the institution specific number of tokens per identity config setting.

## 2.3.2
Bugfix release. Remove call to non existing SecondFactorType::getAvailableSecondFactorTypes

## Release notes from the RMT era

### VERSION 2  UPGRADE GUZZLE TO GUZZLE 6.  AS GUZZLE NO LONGER STRIPS TRAILING SLASHES IN VERSION 6, THE COMMAND API NEEDS TO BE RECONFIGURED BY CONSUMERS. THIS WARRANTS A MAJOR RELEASE.
```
   Version 2.3 - This release supports token registration without email verification
      02/08/2018 16:34  2.3.1  Add additional institution config second factor validation
      01/19/2018 10:00  2.3.0  initial release

   Version 2.2 - This release exposes a new middleware api endpoint.
      20/11/2017 10:31  2.2.3  Do not provide file name extension in getFileName method of RaSecondFactorExportQuery
      17/11/2017 15:02  2.2.0  initial release

   Version 2.1 - Support requested-at date for verified second factors
      16/11/2017 08:28  2.1.0  initial release

   Version 2.0 - Upgrade Guzzle to Guzzle 6.  As Guzzle no longer strips trailing slashes in version 6, the Command API needs to be reconfigured by consumers. This warrants a major release.
      07/03/2017 15:10  2.0.0  initial release
```

### VERSION 1  RELEASE 1.0

```
   Version 1.6 - Added support for searching for RA candidates with specific second factor types
      22/02/2017 16:55  1.6.0  initial release

   Version 1.5 - Added allowed second factor list
      14/02/2017 17:07  1.5.0  initial release

   Version 1.4 - Removed capabilities that are not provided by MW anymore
      08/08/2016 17:11  1.4.0  initial release

   Version 1.3 - New api capabilities
      03/08/2016 10:56  1.3.0  initial release

   Version 1.2 - RA Locations
      22/06/2016 16:14  1.2.2  Fixed 1.2.1 release
      22/06/2016 16:10  1.2.1  Shows RA locations instead of Has personal ra locations
      22/06/2016 11:16  1.2.0  initial release

   Version 1.1 - Add ProveU2fDevicePossessionCommand and documentNumber to RaSecondFactor
      09/06/2016 10:15  1.1.0  initial release

   Version 1.0 - Release 1.0
      19/06/2015 11:21  1.0.0  initial release
```

### VERSION 0  FIRST PILOT RELEASE

```
   Version 0.3 - Release for second Pilot
      04/05/2015 13:55  0.3.0  initial release

   Version 0.2 - Pass registration authority with vetting command
      26/03/2015 15:09  0.2.0  initial release

   Version 0.1 - First pilot release
      26/03/2015 14:05  0.1.0  initial release
```
