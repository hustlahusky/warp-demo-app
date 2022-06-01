# Warp Components Demo

## Get Started

Run containers

```bash
docker-compose up -d
```

Open in [browser: `http://localhost:48888`](http://localhost:48888).

Connect to `app` container to run console commands:

```bash
docker-compose exec app bash
php bin/console list
```

## Use Cases

### Fetch user

[Open `http://localhost:48888/user/a5b14bac-4c41-440d-b1f7-13c7bd90af6c`](http://localhost:48888/user/a5b14bac-4c41-440d-b1f7-13c7bd90af6c)

### List user's pets

[Open `http://localhost:48888/user/a5b14bac-4c41-440d-b1f7-13c7bd90af6c/pets`](http://localhost:48888/user/a5b14bac-4c41-440d-b1f7-13c7bd90af6c/pets)

Add `?age` query parameter to filter pets by age.
[Ex `http://localhost:48888/user/a5b14bac-4c41-440d-b1f7-13c7bd90af6c/pets?age=3`](http://localhost:48888/user/a5b14bac-4c41-440d-b1f7-13c7bd90af6c/pets?age=3)
