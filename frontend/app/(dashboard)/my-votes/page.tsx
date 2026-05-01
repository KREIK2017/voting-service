'use client';

import { useEffect, useState } from 'react';
import api from '@/lib/api';
import { Vote } from '@/lib/types';
import Link from 'next/link';
import { Calendar, CheckCircle, ChevronRight } from 'lucide-react';
import { useTranslation } from '@/components/LanguageContext';

export default function MyVotesPage() {
  const [votes, setVotes] = useState<Vote[]>([]);
  const [loading, setLoading] = useState(true);
  const { t } = useTranslation();

  useEffect(() => {
    const fetchMyVotes = async () => {
      try {
        const response = await api.get('/my-votes');
        setVotes(response.data.data);
      } catch (error) {
        console.error('Failed to fetch my votes', error);
      } finally {
        setLoading(false);
      }
    };

    fetchMyVotes();
  }, []);

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-3xl font-bold text-gray-900">{t.my_votes.title}</h1>
        <p className="text-gray-600 mt-2">{t.my_votes.subtitle}</p>
      </div>

      {loading ? (
        <div className="space-y-4">
          {[1, 2, 3].map((i) => (
            <div key={i} className="animate-pulse bg-gray-200 h-24 rounded-xl"></div>
          ))}
        </div>
      ) : (
        <div className="space-y-4">
          {votes.map((vote) => (
            <div key={vote.id} className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
              <div className="flex-1">
                <h3 className="text-lg font-bold text-gray-900 mb-1">{vote.poll?.title}</h3>
                <div className="flex items-center space-x-4 text-sm text-gray-500">
                  <div className="flex items-center">
                    <CheckCircle className="w-4 h-4 text-green-500 mr-1" />
                    <span>{t.my_votes.voted_for}: {vote.option?.text}</span>
                  </div>
                  <div className="flex items-center">
                    <Calendar className="w-4 h-4 mr-1" />
                    <span>{new Date(vote.created_at).toLocaleDateString()}</span>
                  </div>
                </div>
              </div>
              <Link
                href={`/polls/${vote.poll_id}/results`}
                className="ml-4 p-2 text-gray-400 hover:text-indigo-600 rounded-full hover:bg-indigo-50 transition-colors"
              >
                <ChevronRight className="w-6 h-6" />
              </Link>
            </div>
          ))}
          {votes.length === 0 && (
            <div className="py-20 text-center bg-white rounded-2xl border border-dashed border-gray-300">
              <p className="text-gray-500 italic text-lg">You haven't voted in any polls yet.</p>
              <Link href="/polls" className="mt-4 inline-block text-indigo-600 font-medium hover:underline">
                Explore active polls
              </Link>
            </div>
          )}
        </div>
      )}
    </div>
  );
}
