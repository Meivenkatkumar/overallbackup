# [Vidyo.io](http://vidyo.io) Simple Room sample running on Google App Engine

This Google App Engine sample illustrates the use of [Vidyo.io](http://vidyo.io) which lets endpoints join meeting rooms of their own choosing using the VidyoConnector.

For more information on [Vidyo.io](http://vidyo.io) see the [Developer](https://developer.vidyo.io) section.

## Getting Started

1. Download this sample from the git repository
   * `git clone https://github.com/Vidyo/gae-simple-room.git`
1. Grab your **Developer Key** and **Appliation ID** from [Vidyo.io](http://vidyo.io)
   * See **API Key** section in the [Dashboard](https://developer.vidyo.io/dashboard).
1. Modify the `main.py` to add your [Vidyo.io](http://vidyo.io) credentials
   * Change the value of `VIDYO_IO_DEVELOPER_KEY` to your **Developer Key**
   * Change the value of `VIDYO_IO_APPLICATION_ID` to your **Appliation ID**
1. Run the App
   * See Google App Engine [Tutorials](https://cloud.google.com/appengine/docs/python/tutorials) and a similar [Guestbook](https://cloud.google.com/appengine/docs/python/getting-started/creating-guestbook) example for more info.

## Application Details

The application has two components, the index.html page and the room.html page.

### index.html

This is the landing page which presents the user with a dialog box to enter their "Display Name" and "Room Name". Once the **Join** button is clicked, this page redirects to the room path ex:`/demoRoom` which is served by the `room.html`. 

### room.html

This page is served served by `main.py` when the path contains a room name ex: `/demoRoom`. The `main.py` invokes the `Room()` class and  performs the following:

1. Base64 and URL encodes the roomId ex:`demoRoom` in case it has spaces and special characters.
1. Checks the version number. When not provided as a query parameter, it will default to the `latest` version of the `VidyoConnector.html`.
1. Creates a random `userName`. For this example only the `DisplayName` is known so we create a random username for our Vidyo.io token.
1. Generates a Vidyo.io token from the `userName` using the `getVidyoIOToken()` function.
1. Creates a template variable `url_vidyoio` which contains a URL link to the `VidyoConnector.html` with all the proper query parameters.
1. The Template engine will generate an HTML file with an IFRAME that points to the `url_vidyoio` described in `room.html`.

### Parameters

This sample can take the same parameters that are available for `VidyoConnector.html`. See `VidyoConnector` -> `Web` section of Vidyo.io [Developer](https://developer.vidyo.io) documentation.

In Addition, the version number can be specified by the `version` query parameters. ex: `/demoRoom?version=4.1.4.7`
