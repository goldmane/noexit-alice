Server
======

#####Overview
The server component consists of a NodeJS application that serves data via the Express Router. It is set up to use data from a MongoDB data store, but could be altered to use any data store.

#####REST API
The [web.js](https://github.com/goldmane/noexit-alice/blob/master/server/web.js) file is the heart of this module. It creates the REST routes, connection to the MongoDB, and establishes the data models used throughout.
The routes are organized by data model for the most part and are in individual files in the [routes](https://github.com/goldmane/noexit-alice/tree/master/server/routes) folder.

#####Data Models
The [models](https://github.com/goldmane/noexit-alice/tree/master/server/models) folder contains various Mongoose templates that should be fairly self-explanatory. These should give an idea of what the individual MongoDB collections looked like for our use.

#####How to use
This project was built for an extremely specific purpose and should not be used as-is. But, please feel free to use whatever you would like from it.

In the [web.js](https://github.com/goldmane/noexit-alice/blob/master/server/web.js) file, there are db/credential __vars__ that need to be filled out. These are set up to use MongoDB. Use the .js files in the [models](https://github.com/goldmane/noexit-alice/tree/master/server/models) folder to build out the MongoDB collection schemas.
