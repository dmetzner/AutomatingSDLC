# Refactoring of test system and alternative API testing

Description: The test system needs to be refactored and split up to speed up testing processes with the already existing docker container. Tests need to be split up in more sections/test suits so that we can execute them in parallel using our docker container The underlying FeatureContextFiles need to be refactored, cleaned, adapted where necessary and code duplications need to be removed Since we are on our way to introduce APIv2, it is a good time to check if our current Testsuite (Behat) is adequately equipped to extensively test our new API. If not an alternative should be found and implemented (in accordance with the rest of the team)

## Why Unit testing?

Testing can be automated for speed.

Higher probability of bug free code

Easy to understand inherited code

Refactor code to the point of testability and understandability

Figure out what went wrong quickly with test cases

New features can be coded easily

It also minimizes the cost of change in software.

## Why PHPUnit testing?

Just like other test automation frameworks meant for unit testing, PHPUnit helps you in developing a code that performs well and is easy to maintain.

It also helps you to identify defects that may arise before the code is pushed to further testing phases.

Issues are detected early during development phase since the testing is carried out by developers only.

Unit testing helps in detecting issues and fixing the code at a certain fragment of the application, thereby leaving other fragments intact and without any chance of breakage.

Debugging process is made simpler. Debugging is required only when a certain unit test fails.

## Disadvantages

The only disadvantage of PHPUnit is that, for testing multiple functions, the developer is required to add cover annotations.

By any chance, if you change the name of the method or function without updating the @covers annotation, testing is skipped for that certain method or function.

## ApiPHPUnit Tests

Since we found out that the PhpUnit tests are the best alternative for our current Testsuite, because they are significantly faster than Behat tests, it was used for this project. 

First, some missing routes were added, which helped to find out more about our APIs:
[SHARE-314 Extend media lib API](https://github.com/Catrobat/Catroweb/pull/622)

Then, first part of tests was added: [MediaAPI PhpUnit Tests](https://github.com/Catrobat/Catroweb/pull/681).

After adding other tests and some refactoring, tests were all done in: [API PhpUnit Tests](https://github.com/Catrobat/Catroweb/pull/760).

## Testing: 

For the purposes of these tests **WebTestCase** class, fake database entries and PHP Unit assertions were used.

For every route for media files both response status and response body were checked.

For routes for users and projects it was only possible to check response status because projects and users had different ID every time when created.

## Result: 

Not only that these tests run for only few minutes, they are also a lot easier to understand or write than Behat tests.

## Things I learned:

API in Symfony

Behat tests

Unit tests

PHPUnit tests

Git

## Other work: 

After testing part there was a lot of hours left so the following tickets were also done:

[SHARE-255/Refactoring - Adminarea - Tools/Maintain](https://github.com/Catrobat/Catroweb/pull/779)

[Edit php unit tests annotations, tests refactoring, add admin command test](https://github.com/Catrobat/Catroweb/pull/805)

[SHARE-341/CAPI_Entities_Inconsistency](https://github.com/Catrobat/Catroweb/pull/818)

[SHARE-345/Updated_bricks](https://github.com/Catrobat/Catroweb/pull/850)

