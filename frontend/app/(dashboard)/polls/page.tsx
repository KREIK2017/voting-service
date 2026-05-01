'use client';

import { useEffect, useState } from 'react';
import { useTranslation } from '@/components/LanguageContext';
import api from '@/lib/api';
import { Poll } from '@/lib/types';
import PollCard from '@/components/PollCard';

export default function VoterPollsPage() {
  const [polls, setPolls] = useState<Poll[]>([]);
  const [loading, setLoading] = useState(true);
  const { t } = useTranslation();

  useEffect(() => {
    const fetchPolls = async () => {
      try {
        const response = await api.get('/polls');
        setPolls(response.data.data);
      } catch (error) {
        console.error('Failed to fetch polls', error);
      } finally {
        setLoading(false);
      }
    };

    fetchPolls();
  }, []);

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-3xl font-bold text-gray-900">{t.polls.title}</h1>
        <p className="mt-2 text-gray-600">{t.polls.subtitle}</p>
      </div>

      {loading ? (
        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {[1, 2, 3].map((i) => (
            <div key={i} className="animate-pulse bg-gray-200 h-64 rounded-xl"></div>
          ))}
        </div>
      ) : (
        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {polls.map((poll) => (
            <PollCard key={poll.id} poll={poll} />
          ))}
          {polls.length === 0 && (
            <div className="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-gray-300">
              <p className="text-gray-500 italic text-lg">No active polls found right now. Check back later!</p>
            </div>
          )}
        </div>
      )}
    </div>
  );
}
