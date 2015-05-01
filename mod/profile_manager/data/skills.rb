require 'json'
require 'digest'

# reference: https://docs.google.com/spreadsheets/d/1LFs5nO-Jq2erDH7YwoUSwEfYkA8hTCZtlVl66wWrgOk/edit?usp=sharing

# Possible widget names:

# files
# mindmaps
# questions
# hypothesis
# pages
# conclusions
# notes
# discussion
# reflection
# data_collection
# fca

# WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING

# ***********************************************************************************************************************************
#                                                                                                                                   *
# WARNING: don't change task names because that will change the activity id and the right widgets won't load in existing inquiries  *
#                                                                                                                                   *
# ***********************************************************************************************************************************

# WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING  WARNING

phases = [

  {
    name: 'question/hypothesis',
    tasks: [
        {
          name: 'embedding',
          skills: ['critical thinking', 'critical thinking (comprehension)'],
          activity: "Providing a wonder-moment, a 'My theory is' (Idea) and/or Formulating a good hypothesis",
          title: 'Wonder moment',
          widget: 'questions'
        },
        {
          name: 'context',
          skills: ['research (observation)'],
          activity: "Conducting a search for sources / literature research",
          title: 'Specify context',
          widget: 'notes'
        },
        {
          name: 'existing knowledge',
          skills: ['information literacy (existing knowledge, learning, argumentation)'],
          activity: "Literature research/ describing 'What we (already) know'",
          title: 'Existing knowledge',
          widget: 'files'
        },
        {
          name: 'mental representation',
          skills: ['critical thinking (comprehension)', 'metacognitive'],
          activity: "Concept mapping",
          title: 'Concept map',
          widget: 'mindmaps'
        },
        {
          name: 'language',
          skills: ['critical thinking (comprehension, argumentation)', 'information literacy (existing knowledge, learning, argumentation)', 'communication (language)'],
          activity: "Concept defining",
          title: 'Definitions of concepts',
          widget: 'notes'
        },
        # {
        #   name: 'field of research',
        #   skills: ['critical thinking (comprehension)', 'information literacy (existing knowledge, learning)', 'communication (language)', 'analytical'],
        #   activity: "literature research and concept defining",
        #   title: 'Field of research',
        #   widget: 'fca',
        #   column: 2
        # },
        {
          name: 'meaning of empirical',
          skills: ['critical thinking', 'analytical'],
          activity: "Describing 'What we (still) need to know'",
          title: 'Need to know',
          widget: 'questions',
          column: 2
        },
        {
          name: 'reflection on question',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "Understanding different kinds of scientific questions and examining and evaluating this aspect of the learning experience",
          title: 'Phase 1 Reflection',
          widget: 'reflection',
          column: 2
        }
    ]
  },
  {
    name: 'operationalisation / planning the method',
    tasks: [
        {
          name: 'indicators',
          skills: ['information literacy (existing kowledge, learning, argumentation)'],
          activity: "Coming up with indicators for concepts that can be measured to develop or test ideas (and relationships among them)",
          title: 'Indicators for measuring',
          widget: 'notes'
        },
        {
          name: 'predictions',
          skills: ['critical thinking', 'analytical', 'critical thinking (inferring)'],
          activity: "Formulating hypotheses and coming up with alternative hypotheses",
          title: 'Prediction',
          widget: 'hypothesis'
        },
        {
          name: 'resources',
          skills: ['information literacy (existing knowledge, learning)'],
          activity: "Come up with resources and ways how to measure/ instruments (qualitative and quantitative)",
          title: 'Planning the method',
          widget: 'discussion'
        },
        {
          name: 'methodology',
          skills: ['critical thinking', 'research (observation)'],
          activity: "Set up experiments to test hypotheses or set up other inquiry procedure",
          title: 'Methodology',
          widget: 'files'
        },
        {
          name: 'ethics',
          skills: ['information literacy (existing knowledge, learning)'],
          activity: "Showing ethical concern within research setup",
          title: 'Ethical concerns',
          widget: 'discussion',
          column: 2
        },
        {
          name: 'reflection on operationalisation',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "Checking and coming up with alternative operationalisations and methods, and examining and evaluating this aspect of the learning experience",
          title: 'Phase 2 Reflection',
          widget: 'reflection',
          column: 2
        }
    ]
  },
  {
    name: 'data collection',
    tasks: [
        {
          name: 'information foraging',
          skills: ['information literacy (exisiting knowledge, learning)', 'computer/technical', 'research (scientific)'],
          activity: "Collecting data with (measurable) indicators to develop or test ideas/beliefs",
          title: 'Collect information',
          widget: 'data_collection'
        },
        {
          name: 'systematic observation',
          skills: ['research (observation)', 'research (experimentation)'],
          activity: "Using authoritative resources, systematic measuring of qualitative and quantitative measures (controlling the experiment to minimize alternative influences)",
          title: 'Systematic data collection',
          widget: 'discussion'
        },
        {
          name: 'experimentation',
          skills: ['research', 'research (experimentation)'],
          activity: "Testing a hypothesis/ideas",
          title: 'Description of experiment',
          widget: 'files'
        },
        {
          name: 'tools (data collection)',
          skills: ['computer/technical'],
          activity: "Using appropriate tools to collect data with",
          title: 'Description of data collection tools',
          widget: 'files'
        },
        # {
        #   name: 'simulation',
        #   skills: ['computer/technical', 'research (experimentation)'],
        #   activity: "running experiments by use of computerised tools",
        #   title: '',
        #   widget: 'GO LAB'
        # },
        {
          name: 'data storage',
          skills: ['computer/technical'],
          activity: "Collecting evidence",
          title: 'Evidence',
          widget: 'files'
        },
        {
          name: 'data security',
          skills: ['computer/technical'],
          activity: "Taking privacy of data into consideration",
          title: 'Inquiry discussion (data privacy)',
          widget: 'discussion',
          column: 2
        },
        {
          name: 'documentation',
          skills: ['communication (language)', 'computer/technical', 'communication (writing)'],
          activity: "Careful record keeping of methods and findings",
          title: 'Followed data collection methods',
          widget: 'files',
          column: 2
        },
        {
          name: 'reflection on data collection',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "Reflecting on what knowledge was gained and what has not been collected yet, and examining and evaluating this aspect of the learning experience",
          title: 'Phase 3 Reflection',
          widget: 'reflection',
          column: 2
        }
    ]
  },
  {
    name: 'data analysis',
    tasks: [
        {
          name: 'quantitative',
          skills: ['analytical (quantitative analysis)', 'analytical (statistical)', 'analytical (mathematical)'],
          activity: "Processing measures taken",
          title: 'Calculations done on data',
          widget: 'notes'
        },
        {
          name: 'qualitative',
          skills: ['analytical (statistical)', 'analytical (mathematical)', 'analytical (qualitative analysis)'],
          activity: "Processing the measured perceptions of phenomena, e.g. extracting theme's, clustering",
          title: 'Procedure of categorizing data',
          widget: 'notes'
        },
        {
          name: 'tools (data analysis)',
          skills: ['computer/technical'],
          activity: "Using data analysis tools, like spreadsheets, tables",
          title: 'Analysed data',
          widget: 'files'
        },
        {
          name: 'visualisation',
          skills: ['critical thinking (comprehension)', 'computer/technical'],
          activity: "Using graphs or other visualisations",
          title: 'Graphs/visualisations of data',
          widget: 'files',
          column: 2
        },
        {
          name: 'reflection on data analysis',
          skills: ['critical thinking', 'critical thinking(evaluation)', 'metacognitive'],
          activity: "Checking the analyses and coming up with alternatives, and examining and evaluating this aspect of the learning experience",
          title: 'Phase 4 Reflections',
          widget: 'reflection',
          column: 2
        }
    ]
  },
  {
    name: 'interpretation',
    tasks: [
        {
          name: 'embedding',
          skills: ['critical thinking (comprehension)', 'information literacy (existing knowledge, learning)', 'critical thinking (inferring)', 'analytical (classification)'],
          activity: "Interpreting findings in light of previous knowledge",
          title: 'Fit of findings fit with existing knowledge',
          widget: 'discussion'
        },
        {
          name: 'confirmation/falsification',
          skills: ['critical thinking (evaluation)', 'research (experimentation)', 'analytical (statistical)', 'analytical (mathematical)'],
          activity: "Judging evidence and counterevidence",
          title: 'Counterevidence',
          widget: 'discussion'
        },
        {
          name: 'relevance',
          skills: ['critical thinking', 'analytical'],
          activity: "Making sure the results are relevant to the problem",
          title: 'Relevance of results to problem',
          widget: 'files'
        },
        {
          name: 'reflection of interpretation',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "Checking the interpretation (process) and coming up with alternatives, and examining and evaluating this aspect of the learning experience",
          title: 'Phase 5 Reflections',
          widget: 'reflection',
          column: 2
        }
    ]
  },
  {
    name: 'communication',
    tasks: [
        {
          name: 'writing up',
          skills: ['communication (language)', 'communication (writing)'],
          activity: "Presenting the findings in clear, written form",
          title: 'Conclusion',
          widget: 'conclusions'
        },
        {
          name: 'strategy',
          skills: ['research (planning)', 'research (organisation)'],
          activity: "Considering impact, content, routes and stakeholders",
          title: 'Impact, stakeholders',
          widget: 'discussion'
        },
        {
          name: 'audience',
          skills: ['analytical', 'analytical (classification)', 'research (planning)'],
          activity: "Determining the audience and adjusting presentation mode accordingly",
          title: 'Describe the kind of audiences you will present findings to',
          widget: 'notes'
        },
        {
          name: 'tools',
          skills: ['computer/technical'],
          activity: "Using technical tools for communicating results",
          title: 'Which technical tools can we use to communicate our results?',
          widget: 'notes'
        },
        # {
        #   name: 'dissemination',
        #   skills: ['communication (language)', 'communication (writing)', 'communication (presentation)', 'communication'],
        #   activity: "spreading your findings, getting them noticed",
        #   title: '',
        #   widget: ''
        # },
        {
          name: 'discussion/argumentation',
          skills: ['communication', 'critical thinking'],
          activity: "Discussing the findings in a critical manner (e.g. implicaitons, limitations of approach, lessons for future studies)",
          title: 'Implications, limitations, lessons learned',
          widget: 'discussion',
          column: 2
        },
        {
          name: 'feedback',
          skills: ['communication'],
          activity: "Providing feedback on findings of others",
          title: 'Feedback',
          widget: 'files',
          column: 2
        },
        {
          name: 'reflection',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "Checking the method of communication and coming up with alternative approaches, and examining and evaluating this aspect of the learning experience",
          title: 'Phase 6 Reflection',
          widget: 'reflection',
          column: 2
        }
    ]
  }

]

mapper = {
  'files' => 'filerepo',
  'mindmaps' => 'wespot_mindmeister',
  'questions' => 'answers',
  'hypothesis' => 'hypothesis',
  'pages' => 'pages',
  'conclusions' => 'conclusions',
  'notes' => 'notes',
  'reflection' => 'reflection',
  'discussion' => 'group_forum_topics',
  'data_collection' => 'wespot_arlearn',
  'fca' => 'wespot_fca'
}

puts "FRIENDLY REMINDER: you shouldn't change task names because the widget (activity) IDs are generated from them. You haven't right?"

activites = []

phases.each_with_index do |phase, index|
  phase[:phase] = index + 1
  phase[:tasks].each_with_index do |task, task_index|
    act = Digest::MD5.hexdigest("#{index} #{task[:name]}")[0..4]
    if activites.include?(act)
      puts "GENERAL FAILURE duplicate activity ID generated at TASK NAME=#{task[:name]} - check if two tasks have the same name"
      puts "exiting"
      exit
    end
    activites << act
    task[:activity_id] = act
    task[:activity] = task[:activity].capitalize
    if task[:widget].to_s != ''
      result = mapper[task[:widget]]
      if result
        task[:widget] = result
      else
        puts "Unknown widget: #{task[:widget]}"
      end
    end
    #task[:name] = task[:name].strip.gsub(' ', '_')
    task[:order] = task_index + 1
  end
end

File.open('skills.json', 'w') do |f|
  f.write JSON.pretty_generate(phases)
end

# out = File.open('skills.rb', 'w')
# prev = ''

# File.readlines('skills2.rb').each do |line|
#   if prev.include?('activity:')
#     out.puts "          activity_id: '#{Digest::MD5.hexdigest(prev)}'"
#   end
#   out.puts line.include?('activity:') ? "#{line.chomp}," : line
#   prev = line
# end
