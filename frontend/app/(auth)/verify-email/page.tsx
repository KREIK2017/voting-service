'use client';

import Link from 'next/link';
import { Mail } from 'lucide-react';

export default function VerifyEmailPage() {
  return (
    <div className="flex flex-col items-center justify-center min-h-[calc(100vh-8rem)]">
      <div className="w-full max-w-md p-8 text-center bg-white rounded-2xl shadow-xl border border-gray-100">
        <div className="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-6">
          <Mail className="w-8 h-8 text-indigo-600" />
        </div>
        
        <h1 className="text-3xl font-extrabold text-gray-900">Verify Your Email</h1>
        <p className="mt-4 text-gray-600">
          We've sent a verification link to your email address. Please click the link in the email to activate your account.
        </p>
        
        <div className="mt-8 space-y-4">
          <p className="text-sm text-gray-500">
            If you didn't receive an email, check your spam folder or contact support.
          </p>
          
          <div className="pt-6">
            <Link
              href="/login"
              className="text-indigo-600 hover:text-indigo-500 font-medium"
            >
              Back to Login
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
