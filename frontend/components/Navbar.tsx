'use client';

import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import { getUser, logout } from '@/lib/auth';
import { User } from '@/lib/types';

import { useTranslation } from '@/components/LanguageContext';

export default function Navbar() {
  const pathname = usePathname();
  const router = useRouter();
  const [user, setUserState] = useState<User | null>(null);
  const { locale, t, setLocale } = useTranslation();

  useEffect(() => {
    setUserState(getUser());
  }, [pathname]);

  const handleLogout = () => {
    logout();
    setUserState(null);
    router.push('/login');
  };

  return (
    <nav className="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16">
          <div className="flex">
            <Link href="/" className="flex-shrink-0 flex items-center text-xl font-bold text-indigo-600">
              VotingService
            </Link>
            <div className="hidden sm:ml-6 sm:flex sm:space-x-8">
              <Link
                href="/"
                className={`${
                  pathname === '/' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                } inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium`}
              >
                {t.nav.home}
              </Link>
              {user && (
                <>
                  <Link
                    href="/dashboard"
                    className={`${
                      pathname.startsWith('/dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                    } inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium`}
                  >
                    {t.nav.dashboard}
                  </Link>
                  {user.role === 'admin' ? (
                    <Link
                      href="/admin/polls"
                      className={`${
                        pathname.startsWith('/admin') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                      } inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium`}
                    >
                      Manage Polls
                    </Link>
                  ) : (
                    <>
                      <Link
                        href="/polls"
                        className={`${
                          pathname.startsWith('/polls') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                        } inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium`}
                      >
                        {t.nav.available_polls}
                      </Link>
                      <Link
                        href="/my-votes"
                        className={`${
                          pathname.startsWith('/my-votes') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                        } inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium`}
                      >
                        {t.nav.my_votes}
                      </Link>
                    </>
                  )}
                </>
              )}
            </div>
          </div>
          <div className="flex items-center space-x-6">
            <div className="flex items-center bg-gray-100 p-1 rounded-lg">
              <button 
                onClick={() => setLocale('uk')}
                className={`px-2 py-1 text-xs font-bold rounded-md transition-all ${locale === 'uk' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'}`}
              >
                UK
              </button>
              <button 
                onClick={() => setLocale('en')}
                className={`px-2 py-1 text-xs font-bold rounded-md transition-all ${locale === 'en' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'}`}
              >
                EN
              </button>
            </div>

            {user ? (
              <div className="flex items-center space-x-4">
                <span className="text-sm text-gray-700 hidden sm:block font-medium">
                  {user.name} <span className="text-gray-400">|</span> <span className="text-indigo-600">{user.role}</span>
                </span>
                <button
                  onClick={handleLogout}
                  className="bg-red-50 text-red-600 px-4 py-2 rounded-md text-sm font-medium hover:bg-red-100 transition-colors"
                >
                  {t.nav.logout}
                </button>
              </div>
            ) : (
              <div className="flex items-center space-x-4">
                <Link href="/login" className="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                  {t.nav.login}
                </Link>
                <Link
                  href="/register"
                  className="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 shadow-sm transition-all active:scale-95"
                >
                  {t.nav.register}
                </Link>
              </div>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
}
