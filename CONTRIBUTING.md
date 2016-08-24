# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/ownpass/ownpass).


## Pull Requests

- **[PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** - The easiest way to apply the conventions is to install [PHP Code Sniffer](http://pear.php.net/package/PHP_CodeSniffer).
- **Add tests!** - Your patch won't be accepted if it doesn't have tests.
- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- **Consider our release cycle** - We try to follow [SemVer v2.0.0](http://semver.org/). Randomly breaking public APIs is not an option.
- **Create feature branches** - Don't ask us to pull from your master branch.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.


## Running Tests

``` bash
$ composer test
```

## Coding Standards

We verify against [PSR-2](http://www.php-fig.org/psr/psr-2/). To check for CS issues:

```console
$ composer cs-check
```

This will report CS issues. Alternately, you can have most errors automatically
fixed as well:

```console
$ composer cs-fix
```

After running `composer cs-fix`, be sure to add and commit any files that were
updated, after first verifying that the changes do not break tests.

## Conduct

Please see our [CONDUCT.md](CONDUCT.md) to understand expected behavior when interacting with others in the project.
