export default function middleware({ to, from, next, router }) {
    // Every path should return next() otherwise unexpected behaviour might occur.
    next();
}