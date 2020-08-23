# Refactoring of test system and alternative API testing

Description: The test system needs to be refactored and split up to speed up testing processes with the already existing docker container. Tests need to be split up in more sections/test suits so that we can execute them in parallel using our docker container The underlying FeatureContextFiles need to be refactored, cleaned, adapted where necessary and code duplications need to be removed Since we are on our way to introduce APIv2, it is a good time to check if our current Testsuite (Behat) is adequately equipped to extensively test our new API. If not an alternative should be found and implemented (in accordance with the rest of the team)

## ApiPhpUnit Tests

Since we found out that the PhpUnit tests are the best alternative for our current Testsuite, because they are significantly faster than Behat tests, it was used for this project. 

First, some missing routes were added, which helped to find out more about our APIs:
[SHARE-314 Extend media lib API](https://github.com/Catrobat/Catroweb/pull/622)

Then, first part of tests was added: [MediaAPI PhpUnit Tests](https://github.com/Catrobat/Catroweb/pull/681).

After adding other tests and some refactoring, tests were all done in: [API PhpUnit Tests](https://github.com/Catrobat/Catroweb/pull/760).

## Testing: 

For the purposes of these tests **WebTestCase** class and fake database entries were used.

For every route for media files both response status and response body were checked.

For routes for users and projects it was only possible to check response status because projects and users had different ID every time when created.

## Result: 
Not only that these tests run for only few minutes, they are also a lot easier to understand or write than Behat tests.

## Other work: 

After testing part there was a lot of hours left so the following tickets were also done:

[SHARE-255/Refactoring - Adminarea - Tools/Maintain](https://github.com/Catrobat/Catroweb/pull/779)

[Edit php unit tests annotations, tests refactoring, add admin command test](https://github.com/Catrobat/Catroweb/pull/805)

[SHARE-341/CAPI_Entities_Inconsistency](https://github.com/Catrobat/Catroweb/pull/818)

[SHARE-345/Updated_bricks](https://github.com/Catrobat/Catroweb/pull/850)

