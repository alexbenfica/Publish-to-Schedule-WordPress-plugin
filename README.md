# Publish to Schedule WordPress Plugin

This repository contains the source code for the Publish to Schedule WordPress plugin.

## Description

Publish to Schedule is a WordPress plugin that automates the scheduling of blog posts. It allows users to set rules for publishing posts on specific days and times, ensuring consistent content delivery without manual intervention.

## Features

- Automatic post scheduling based on configurable rules
- Support for multiple days of the week and time intervals
- Integration with WordPress block editor and classic editor
- Admin interface for easy configuration
- Translation support (Portuguese and Dutch included)

## Technical Details

### File Structure

- `publish-to-schedule.php`: Main plugin file with core logic
- `pts-metabox.php`: Adds scheduling options to the post editor
- `pts-gutenberg.php`: Compatibility functions for block editor
- `pts-util.php`: Utility functions
- `pts-analytics.php`: Analytics tracking (optional)
- `pts-donate.php`: Donation prompts
- `publish-to-schedule-admin.php`: Admin settings page
- `lang/`: Translation files (.po, .mo, .pot)
- `assets/`: Plugin assets (icons, banners)

### Translations

The plugin supports internationalization. Translation files are located in the `lang/` directory:

- `publish-to-schedule.pot`: Template for translations
- `pt_BR.po/.mo`: Portuguese (Brazil) translations
- `nl_NL.po/.mo`: Dutch translations

To update translations:

1. Regenerate the .pot file using `xgettext` on PHP files
2. Update .po files with `msgmerge`
3. Compile .mo files with `msgfmt`

### Deployment

The plugin is automatically deployed to WordPress.org via GitHub Actions when a version tag is pushed to the repository.

#### Deployment Process

1. Update version numbers in `publish-to-schedule.php` and `readme.txt`
2. Update changelog in `readme.txt`
3. Commit changes
4. Create and push a git tag (e.g., `v4.5.7`)
5. GitHub Actions will trigger the deployment workflow
6. The plugin is uploaded to WordPress.org SVN

#### Required Secrets for Deployment

Set the following in GitHub repository secrets:

- `SVN_USERNAME`: WordPress.org SVN username
- `SVN_PASSWORD`: WordPress.org SVN password

## Installation

1. Download the plugin from WordPress.org
2. Upload to your `wp-content/plugins/` directory
3. Activate through the WordPress admin

## Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This plugin is licensed under GPL-2.0-or-later.

## Links

- [WordPress.org Plugin Page](https://wordpress.org/plugins/publish-to-schedule/)
- [Support](https://wordpress.org/support/plugin/publish-to-schedule/)
- [Donate](https://www.buymeacoffee.com/FQNxAqVUTo)
