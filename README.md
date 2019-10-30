# My Console App

This simple app its my console application for get Football live score &Weather Status also more options are coming soon...

- Football Live Score
- Weather Status

------

## Documentation

This mini application base on the Laravel Zero. To run this app you most to have Rapid api account and have X-RapidAPI-Key token. 

First clone this project and run ``composer install`` create ``.env`` file in root of project and this line into in it:

```Bach
API_KEY=Your X-RapidAPI-Key token here
```

It's Done. for now only 2 command available one for each: live score and Weather.

```ba
php console-app score:live
```

This command for get live score from football api. 

```bash
php console-app weather city-name
```

The weather command most to have city name params like washington. For now the city parametr only accept one city. this weather api base on the open weather api.



## Support the development
**Do you like this project?**

If you like this simple app feel free to contact with me or share your issues in github issue section. Feel free to send your PR.



## License

[MIT license](https://github.com/ybazli/console-app/stable/LICENSE.md).