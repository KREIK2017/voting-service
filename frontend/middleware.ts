import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';

export function middleware(request: NextRequest) {
  const token = request.cookies.get('auth_token')?.value;
  const userCookie = request.cookies.get('auth_user')?.value;
  const user = userCookie ? JSON.parse(userCookie) : null;

  const { pathname } = request.nextUrl;

  // Protected routes
  const isAdminRoute = pathname.startsWith('/admin');
  const isDashboardRoute = pathname.startsWith('/dashboard') || pathname.startsWith('/polls') || pathname.startsWith('/my-votes');
  const isAuthRoute = pathname.startsWith('/login') || pathname.startsWith('/register');

  if (!token && (isAdminRoute || isDashboardRoute)) {
    return NextResponse.redirect(new URL('/login', request.url));
  }

  if (token && isAuthRoute) {
    return NextResponse.redirect(new URL('/dashboard', request.url));
  }

  if (isAdminRoute && user?.role !== 'admin') {
    return NextResponse.redirect(new URL('/dashboard', request.url));
  }

  return NextResponse.next();
}

export const config = {
  matcher: [
    '/dashboard/:path*',
    '/admin/:path*',
    '/polls/:path*',
    '/my-votes/:path*',
    '/login',
    '/register',
  ],
};
