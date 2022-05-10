Command List
===============================

# User

## Create User

Create user using command line. email address and password required for creating user.

```bash
php yii admin/create
```

# RBAC (Role based access control)

For assign all main to a specific user run this command:

```bash
php yii rbac/backend
```

With running this command a prompt as about user email you wants to assign roles and permission to it.

# API

Generate API documenation.

```bash
php yii api
```

After running this command, the result of api documentation exist in api section with this path:

``
http://api.navar...com/frontend/v1
http://api.navar...com/backend/v1
``