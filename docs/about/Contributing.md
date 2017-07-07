# Contributing to QCubed
We are a community-driven PHP framework and welcome your input. Please read through the workflow and standards to learn how to be a better contributor.

* [Road Map](Roadmap.md). Check here for issues that we need help with. There is lots to do.

## Asking Questions
We currently use [StackOverlow](http://stackoverflow.com) as our developer forum for "How To" questions. Tag your question with QCubed and someone should get back to you.

## Reporting Bugs

Look at the [issues](https://github.com/qcubed/framework/issues). If you find something that hasn't already been reported, create a new issue. Here are some guidelines to use when creating a new issue:

* Use the label 'bug'
* Be as descriptive as possible, include as much information about how to recreate the bug as possible.
* Remember we are not all native English speakers, be clear and concise and avoid slang terms or acronyms.
* Be patient for a response. This is entirely a volunteer effort, and most of the collaborators are busy with their own projects. Feel free to ask again in the issue if you are not getting a response.

## Add Documentation

Update the [wiki](https://github.com/qcubed/framework/wiki) or fork the framework, update the PHPDoc comment blocks, then make a pull request. You can also contribute to the tutorial and examples website, the code for which is located in assets/php/examples

## Add Features/Fix Bugs
Feature requests and bug fixes should start out as a conversation on the Issues area in the github repository. If you have an idea, post it there for people to discuss.

If you have a bug report, post it as an issue as well if you are not sure about it. If you are sure, you can always make a pull request, but without some discussion ahead of time, we can't guarantee it will be included.

##Using Git and Github
We encourage everyone that wants to contribute to do so. Anyone can fork the framework and submit pull requests to a feature branch or bugfix branch. These requests will be reviewed by core contributors and applied as features or bugfixes accordingly. If you would like to contribute to QCubed, the following steps will help ensure that your contribution is included in the next release

###1. Setup
To make a pull request, you will need to install git on your development computer and create a free account on Github.com. If you are not familiar with the Git distributed source control system, there are many tutorials out there, and many helpful GUI tools if you don't like the command line. PHPStorm has built-in support for Git, and SmartGit is a great git helper tool as well.

### 2. Fork the Framework Repository
If you are using Composer to install QCubed, you can tell composer to include a Git repository for Qcubed by adding the "-sDev" flag to the end of the *create-project* command.

Otherwise, login to Github and navigate to the [QCubed repository](https://qcubed.github.com/). Click the button in the upper corner to fork the repository, then navigate to the fork that is now in your personal repository. There you will see a link to the repository in the little text field that says how to download the repo. Use that link with the git version on your development computer to checkout your fork. If you are using the command line, it will look something like this:

`git clone git@github.com:myUserName/framework.git`

By default, you will be checking out the *master* branch, which is the current shipping version. If you want to work on a different branch, check that one out.

###3. Create a new branch and push your changes
Next, create a new branch on your development computer, and give it a name appropriate to what you are doing. If a bug fix, put "bugfix-" in front of the name, and if a feature addition, put "feature-" at the front. Git will base your new branch on whatever branch you started with. Add your changes to your local repository, then Commit them, and Push them to your local fork. Your command line will look something like:

`git push origin branchname`

###4. Make a pull request

At this point, Github will automatically launch the travis-ci unit test process, and after a few minutes, you can see whether you changes passed our initial automated testing. Browse to [Github.com](https://github.com/) and you can see if your branch passed. From there, make a pull request to qcubed. Be sure to specify the branch you chose to start your local branch when choosing the branch to make your pull request against.


##Workflow Practices 
QCubed has both a bit of a "continuous integration" mindset, but we still want to protect the master branch, since people rely on that branch as part of on-going development.

All contirbutions made to the master branch must be code reviewed by a core contributor before being committed. Github has a review feature that we should use. Core contributors must make sure only these types of changes happen to the current master:

1. Minor bug fixes.
1. Features that have been tested and do not change core functionality. An example that would be acceptable is a new database adapter, or anything that would be put in a new file.
2. When we are ready for a version change, the community has been notified and is OK with it, and the changes have been thoroughly tested.

Usually there will be an on-going development branch where minor features that are in development, or that need extended testing can be put. This branch will be named with the version number that corresponds to the version that the branch represents. This branch should follow these guidelines:

1. Breaking changes must be clearly identified, and attempts to mitigate any fallout should be made. For example, you could have some mechanism that allows developers to optionally turn on the new feature.
2. Submissions should be code-reviewed when possible.
3. As a release schedule approaches, the community should be notified, and the master branch should be merged into the development branch.
4. The community at a minimum should go through the examples website and test that all examples are still functional, and test any new features that are part of the release.
2. When we are ready for a version change, the community should be notified and give approval for the release.
3. Finally, the new branch can be pushed to master, and the version number bumped.

Major breaking changes should be put in a branch labeled with a new major version number.

Feel free to create branches that have proposed changes, and put them in a branch that has "-experimental" in the name.

If you wish to be a core contributor, please notify the group in an Issue.

##Coding Standards

See the [Coding Standards](Standards.md) document
