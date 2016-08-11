# About `plugins` directory

This directory is managed by the composer plugin installation process.

The files in this directory are override files for the plugin controls that you have
installed. When you install a plugin, the composer will create a file in
this directory if it does not exist. 

The files in this directory are meant to be modified by you. Use this to configure your
plugin and override aspects of the plugin. 

Subsequent composer updates will NOT overwrite the files here. This means that if for 
some reason your plugin has modified its override file, you will not get those changes.
This should happen rarely, but its something you should be aware of, and hopefully the
plugin author has some way of notifying you that such a change has been made, which
might mean you will need to merge the change manually.

The directory structure here should correspond to the the namespace of the plugin,
with `QCubed\Plugin\` being the root.