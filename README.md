# Sitegeist.PaperTiger
## Editor defined forms for Neos CMS

This is a prototype to evaluate the concept of editor forms via fusion. Everything in here is WIP and may change at any time without notice.

### Authors & Sponsors

* Martin Ficzel - ficzel@sitegeist.de

*The development and the public-releases of this package is generously sponsored by our employer http://www.sitegeist.de.*

## Installation

Sitegeist.PaperTiger is available via packagist run `composer require sitegeist/papertiger` to install.

We use semantic-versioning so every breaking change will increase the major-version number.

## Usage

## Configuration

### Adjust renderer

```neosfusion
prototype(Sitegeist.PaperTiger:Form) {
    class = 'form my-8'
}

prototype(Sitegeist.PaperTiger:Error) {
    customErrorClass = 'form-errors'
}

prototype(Sitegeist.PaperTiger:SubmitButton) {
    class  = 'submit mt-8'
}
```

### Replace the rendering of a field

### Allow content inside a form

```yaml
'Neos.Demo:Content.Text':
  superTypes:
    'Sitegeist.PaperTiger:Field.Constraint': true

'Neos.Demo:Content.Image':
  superTypes:
    'Sitegeist.PaperTiger:Field.Constraint': true
```

### Add a new field NodeType

### Add a new action NodeType

## Contribution

We will gladly accept contributions. Please send us pull requests.

## License

See [LICENSE](LICENSE)
