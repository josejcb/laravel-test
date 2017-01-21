# laravel-test
# Grouping closest locations to others by zipcodes (using coordinates)

A list of contacts is splitted by this app into 2 groups, based on their location using the zip code parameter. 

Here is the live test hosted: http://laraveltest.pictorapps.com.ve/

The main functionality is located in app/Http/Controllers/MainController.php, explained through comments in there.

No libraries were used and no databases. So, you can download and play. The app reads data from a CSV file located in public directory.

Just PHP and the Laravel Framework facilities were used.

To obtain coordinates searching by Zip Codes, it could be used Google maps Api (like this  https://maps.googleapis.com/maps/api/geocode/json?components=postal_code:98456&key=AIzaSyDC7yaWrM9wkoATWecfqQo8RU7HKgVqb0c), other API or a big database with several Zip codes related to coordinates where they belongs. This is explained in MainController comments. All in all, to simplify this test the coordinates were generated randomly based on latitude range -90 to 90, and longitude -180 to 180.

Josejcb
