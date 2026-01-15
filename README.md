# Alternote (alpha)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/brantje/nextnote/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/brantje/nextnote/?branch=master)

This application is a fork of [NextNote](https://github.com/brantje/nextnote),<br>
which is a rewritten version of [ownNote](https://github.com/Fmstrat/ownnote).<br>

![alpha-release](https://user-images.githubusercontent.com/1787238/38750516-0adbd372-3f0a-11e8-9120-6521cf3232a5.gif)<br>
**If you value your notes please wait for a stable version.**

Changes:
- Forked from brantje/nextnote

## Pull requests are very welcome!
Did you find a bug? Report it or fix it and send a PR.

## Screenshots
![save-as-pdf-example-smaller](https://user-images.githubusercontent.com/1787238/38752055-c6f0db1c-3f0e-11e8-8b57-920f1a709b38.gif)

![recipe-example](https://user-images.githubusercontent.com/1787238/38751463-0fb851c4-3f0d-11e8-867e-0db143f730e1.png)

![nextnote-drag-and-drop-example-bigger](https://user-images.githubusercontent.com/1787238/38581663-58ecc3b8-3cc2-11e8-9011-970ace95a5f5.gif)

## Features
- Full fledged [WYSIWYG editor](https://github.com/tinymce/tinymce)
- Share a note with a user or group
- Note grouping/categorization
- Archive notes

## Todo:
- [ ] Modernize code: It's currently targeted for Nextcloud 14. Need to bring it up to NC 32.
- [X] Rename namespace from NextNotes to Alternote
- [x] Refactor backend to make use of:
  - [x] Entity's
  - [x] Mappers
  - [x] Services
- [x] Switch to a AngularJS frontend
- [ ] Implement [note sharing](https://github.com/brantje/nextnote/issues/81)
- [ ] Import from Evernote as HTML or [ENEX](https://github.com/brantje/nextnote/issues/75)
- [ ] Ability to [save files to a folder as HTML files](https://github.com/brantje/nextnote/issues/96) (untested)
- [ ] Implement hierarchical structure for groups (PR's welcome!)
- [ ] Add markdown support
- [ ] Import from [Notes](https://github.com/nextcloud/notes) app.
- [ ] Switch between database or file mode
- [ ] Add admin section for allowed image / video domains due CSP.
- [ ] Encrypted notes? (What about sharing?)
- [ ] Travis tests (We really need help with this, so PR's welcome!) 
- [ ] Develop an app for Android/iOS (We really need help with this, so PR's welcome!)
   
## Installation
- Place this app in **nextcloud/apps/nextnote** (Rename the extracted ZIP to "nextnote" or you will receive errors)
- Note: *custom_csp_policy* changes are no longer required

#### Scripted installation

You can also use this script developed by [enoch85](https://github.com/enoch85):
```
#!/bin/bash

# Variables
DISTRO=$(lsb_release -sd | cut -d ' ' -f 2)
OS=$(uname -v | grep -ic "Ubuntu")

# Functions
# Whiptail auto-size
calc_wt_size() {
    WT_HEIGHT=17
    WT_WIDTH=$(tput cols)

    if [ -z "$WT_WIDTH" ] || [ "$WT_WIDTH" -lt 60 ]; then
        WT_WIDTH=80
    fi
    if [ "$WT_WIDTH" -gt 178 ]; then
        WT_WIDTH=120
    fi
    WT_MENU_HEIGHT=$((WT_HEIGHT-7))
    export WT_MENU_HEIGHT
}

msg_box() {
local PROMPT="$1"
    whiptail --msgbox "${PROMPT}" "$WT_HEIGHT" "$WT_WIDTH"
}

################

# Check Ubuntu version
echo "Checking server OS and version..."
if [ "$OS" != 1 ]
then
msg_box "Ubuntu Server is required to run this script.
Please install that distro and try again.
You can find the download link here: https://www.ubuntu.com/download/server"
    exit 1
fi

if ! version 16.04 "$DISTRO" 18.04.4; then
msg_box "Ubuntu version $DISTRO must be between 16.04 - 16.04.4"
    exit 1
fi

# Install git if not existing
if [ "$(dpkg-query -W -f='${Status}' "git" 2>/dev/null | grep -c "ok installed")" != "1" ]
then
    apt update -q4
    apt install git -y
fi

pull() {
cd /var/www/nextcloud/apps || exit
rm -rf nextnote
git clone https://github.com/brantje/nextnote.git nextnote
chown -R www-data:www-data /var/www/nextcloud/apps/nextnote
sudo -u www-data php /var/www/nextcloud/occ app:enable nextnote
}

if pull
then
    exit
else
    echo "not sucessfull pull $(date)" > /var/log/nextnote_pull.log
fi
```

Simply just run it everytime you want to get the latest master code.

## Development

Alternote uses a single `.js` file for the templates.   
This gives the benefit that we don't need to request every template with XHR.
For CSS we use SASS so you need ruby and sass installed.
`templates.js` and the CSS are built with grunt, so don't edit them as your changes will be overwritten next time grunt is ran.   
To watch for changes use `grunt watch`
