const permissionsMiddleware = (to, from, next) => {
    if (!to.meta.permissions.includes(userRole)) {
        next({name: 'home'}); // or any other route
    } else {
        next();
    }
};
