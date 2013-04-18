instructor-web-service-installer-for-gsas
=========================================

Installer for Instructor Web Service for GSAS

The installer scripts require BitRock InstallBuilder 8.5 or better to build properly. These scripts will build installers that will work for Windows (BitNami WAMPStack), Linux (BitNami LAMPStack), and Mac OS X (BitNami MAMPStack).

Before building, be sure to populate the /onpar/htdocs subfolder with the files in the instructor-web-service-for-gsas repository. Otherwise, there will be nothing to install!

To build the installer, open onpar-module.xml with BitNami InstallBuilder. Click on "Packaging", select the platform target, then click "Build".