# WP Reset Pro

<h1 align="left">Hey 👋 What's up?</h1>

###

<div align="center">
  <img src="https://github-readme-stats.vercel.app/api?username=QusaiALBahri&hide_title=false&hide_rank=false&show_icons=true&include_all_commits=true&count_private=true&disable_animations=false&theme=dracula&locale=en&hide_border=false&order=1" height="150" alt="stats graph"  />
  <img src="https://github-readme-stats.vercel.app/api/top-langs?username=QusaiALBahri&locale=en&hide_title=false&layout=compact&card_width=320&langs_count=5&theme=dracula&hide_border=false&order=2" height="150" alt="languages graph"  />
</div>

###

<p align="left">My name is Qusai and I'm a tech coach and trainer, from 🇯🇴 🪐<br>About me:<br>✨ Creating bugs since: LTA  📚 I'm currently learning what good 🎯 Goals: +1 🎲 Fun fact: NA</p>

###

<div align="left">
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg" height="40" alt="python logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/jupyter/jupyter-original.svg" height="40" alt="jupyter logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/pytorch/pytorch-original.svg" height="40" alt="pytorch logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/numpy/numpy-original.svg" height="40" alt="numpy logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/pycharm/pycharm-original.svg" height="40" alt="pycharm logo"  />
  <img width="12" />
  <img src="https://skillicons.dev/icons?i=aws" height="40" alt="amazonwebservices logo"  />
  <img width="12" />
  <img src="https://cdn.simpleicons.org/anaconda/44A833" height="40" alt="anaconda logo"  />
  <img width="12" />
  <img src="https://cdn.simpleicons.org/blender/F5792A" height="40" alt="blender logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cplusplus/cplusplus-original.svg" height="40" alt="cplusplus logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/canva/canva-original.svg" height="40" alt="canva logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/git/git-original.svg" height="40" alt="git logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg" height="40" alt="github logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/wordpress/wordpress-original.svg" height="40" alt="wordpress logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/woocommerce/woocommerce-original.svg" height="40" alt="woocommerce logo"  />
</div>

###

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/QusaiALBahri/QusaiALBahri/output/pacman-contribution-graph-dark.svg">
  <source media="(prefers-color-scheme: light)" srcset="https://raw.githubusercontent.com/QusaiALBahri/QusaiALBahri/output/pacman-contribution-graph.svg">
  <img alt="pacman contribution graph" src="https://raw.githubusercontent.com/QusaiALBahri/QusaiALBahri/output/pacman-contribution-graph.svg">
</picture>

###

<div align="left">
  <a href="https://www.linkedin.com/in/qusai-albahri/" target="_blank">
    <img src="https://raw.githubusercontent.com/maurodesouza/profile-readme-generator/master/src/assets/icons/social/linkedin/default.svg" width="52" height="40" alt="linkedin logo"  />
  </a>
  <a href="https://facebook.com/qusai.albahri" target="_blank">
    <img src="https://raw.githubusercontent.com/maurodesouza/profile-readme-generator/master/src/assets/icons/social/facebook/default.svg" width="52" height="40" alt="facebook logo"  />
  </a>
</div>

###
A production‑ready reset tool for WordPress with **full/partial reset**, **optional backups**, **scheduling**, **role‑based access**, **history log**, and a **Status/Health** page.

## Requirements
- WordPress 6.4+
- PHP 8.1+

## Features
1. Full site reset (database + uploads) with multi‑step confirmations.
2. Partial reset (content only, settings only, users only).
3. Optional backup (DB dump + uploads zip) before destructive operations.
4. Reset scheduling via WP‑Cron (maintenance windows).
5. Role‑based access: only admins; multisite requires super admin for resets.
6. Reset history tracking with timestamps and operator identity.
7. Status/Health page with environment and safety checks.

## Safety
- Strong confirmation dialog requires typing a phrase and ticking checkboxes.
- Nonces + capability checks on all actions.
- Option to create a backup before any reset.

## GDPR
Implements exporter/eraser for plugin data (reset history/events).

## Install
1. Copy folder to `wp-content/plugins/wp-reset-pro/`.
2. Run `composer dump-autoload` (if developing).
3. Activate in **Plugins**.

## Developer Notes
- PSR‑4 namespaces under `WPResetPro\`.
- See `src/Admin/SettingsPage.php` for Settings API usage.
- See `src/Core/ResetManager.php` for reset operations.

## Uninstall
If **Delete data on uninstall** is enabled, all plugin data (options, logs, cron hooks) will be removed.
