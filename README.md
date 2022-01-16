# Nette AdminLTE Extension

[AdminLTE v3](https://adminlte.io/)

### Options:
- Simple configuration of sidebar menu

## Usage:

### Install
To install latest version of this extension, use [Composer](https://getcomposer.com/)

```
composer require patriksuchovsky/adminlte
```

### Setup

In config file (for example ```config/common.neon```) add to ```extensions```:
```
extensions:
    adminlte: Patriksuchovsky\Adminlte\DI\Nette\AdminLteExtension
```

### Configuration

Simple example:

```
adminlte:
    menu:
        home:
            name: Home
            link: 'Homepage:default'
            icon: fa fa-th
```

## Sidebar Menu

### Setup

In ```BasePresenter```:

Inject in ```class```:

```
/** @inject @var \Patriksuchovsky\Adminlte\Menu */
public $menu;
```

Insert in ```beforeRender()```:

```
public function beforeRender()
{
    $this->template->menu = $this->menu->displayMenu($this->user, $this);
}
```

In ```layout.latte``` display menu:

```
<!-- Sidebar Menu -->
<nav class="mt-2">
    {$menu}
</nav>
<!-- /.sidebar-menu -->
```

### Configuration

Menu is configured in:
```
adminlte:
    menu:
```

In menu, configure items to display in sidebar menu.
Every item has to have unique name:

```
adminlte:
    menu:
        unique_name:
```

Order of items is set by order in config.

There is 3 types of items:
- link
- header
- submenu (using as ```sub``` in config)

#### Link

Options:
- name (required)
  - displayed name
- link (required)
  - Nette link
- icon (required)
  - icon, that is added to class in ```<i class="nav-icon ...icon... "></i>```
- resource
  - if set, this item is showing only if user is allowed to resource
  - it's verified by ```$presenter->user->isAllowed( resource )```
- badge
  - on right side show badge
  - options for badge:
    - name (required)
      - name for data
    - color (required)
      - color of badge

Example:

```
adminlte:
    menu:
        unique_name:
            name: Home
            link: 'Homepage:default'
            icon: fa fa-th
            resource: admin
            badge:
                name: news
                color: primary
```

Badge:

If you want to add badge, you have to add data as array of badge names in ```beforeRender()```:

```
public function beforeRender()
{
    $data = [
        'news' => 5 //this shows "5" in badge
    ];

    $this->template->menu = $this->menu->displayMenu($this->user, $this, $data);
}
```

#### Header

Options:
- type (required)
  - has to be set as ```header```
- name (required)
  - displayed name

Example:

```
adminlte:
    menu:
        others:
            type: header
            name: OTHERS
```

#### Submenu

Options:
- type (required)
  - has to be set as ```sub```
- name (required)
  - displayed name
- icon (required)
  - icon, that is added to class in ```<i class="nav-icon ...icon... "></i>```
- sub (required)
  - array of links in submenu (also has to have unique name)
  - options are same as link type
  
```
adminlte:
    menu:
        homepage:
            type: sub
            name: Menu bar
            icon: fa fa-bars
            sub:
                 link_a:
                     name: A Home
                     link: 'Error:default'
                     icon: fa fa-th
                 link_b:
                     name: B Home
                     link: 'Error:default'
                     icon: fa fa-th
```
