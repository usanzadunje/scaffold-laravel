// Navigation guard responsible for calling middlewares before each route gets resolved
router.beforeEach((to, from, next) => {
    let middleware = to.meta?.middleware;

    if(to.matched?.length > 1) {
        middleware = [
            ...middleware,
            ...to.matched[0]?.meta?.middleware,
        ];
    }

    // Context sent to middlewares and pipeline
    const context = { to, from, next, router };

    // If no middlewares just proceed to the route
    if(!middleware) {
        return next();
    }

    // Call 1st middleware and provide make next pipeline call
    middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1),
    });
});