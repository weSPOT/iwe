require 'json'

phases = [

  {
    name: 'question/hypothesis',
    tasks: [
        {
          name: 'embedding',
          skills: ['critical thinking', 'critical thinking (comprehension)'],
          activity: "Providing a wonder-moment, a 'My theory is' (Idea) and/or Formulating a good hypothesis"
        },
        {
          name: 'context',
          skills: ['research (observation)'],
          activity: "Conducting a search for sources / literature research"
        },
        {
          name: 'existing knowledge',
          skills: ['information literacy (existing knowledge, learning, argumentation)'],
          activity: "Literature research/ describing 'What we (already) know'"
        },
        {
          name: 'mental representation',
          skills: ['critical thinking (comprehension)', 'metacognitive'],
          activity: "concept mapping"
        },
        {
          name: 'language',
          skills: ['critical thinking (comprehension, argumentation)', 'information literacy (existing knowledge, learning, argumentation)', 'communication (language)'],
          activity: "concept defining"
        },
        {
          name: 'field of research',
          skills: ['critical thinking (comprehension)', 'information literacy (existing knowledge, learning)', 'communication (language)', 'analytical'],
          activity: "literature research and concept defining"
        },
        {
          name: 'meaning of empirical',
          skills: ['critical thinking', 'analytical'],
          activity: "describing 'What we (still) need to know'"
        },
        {
          name: 'reflection on question',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "understanding different kinds of scientific questions and examining and evaluating this aspect of the learning experience"
        }
    ]
  },
  {
    name: 'operationalisation / planning the method',
    tasks: [
        {
          name: 'indicators',
          skills: ['information literacy (exisitng kowledge, learning, argumentation)'],
          activity: "Coming up with indicators for concepts that can be measured to develop or test ideas [and relationships among them]"
        },
        {
          name: 'predictions',
          skills: ['critical thinking', 'analytical', 'critical thinking (inferring)'],
          activity: "Formulating hypotheses and coming up with alternative hypotheses"
        },
        {
          name: 'resources',
          skills: ['information literacy (existing knowledge, learning)'],
          activity: "come up with resources and ways how to measure/ instruments (qualitative and quantitative)"
        },
        {
          name: 'methodology',
          skills: ['critical thinking', 'research (observation)'],
          activity: "set up experiments to test hypotheses or set up other inquiry procedure"
        },
        {
          name: 'ethics',
          skills: ['information literacy (existing knowledge, learning)'],
          activity: "showing ethical concern within research setup"
        },
        {
          name: 'reflection on operationalisation',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "checking and coming up with alternative operationalisations and methods, and examining and evaluating this aspect of the learning experience"
        }
    ]
  },
  {
    name: 'data collection',
    tasks: [
        {
          name: 'information foraging',
          skills: ['information literacy (exisiting knowledge, learning)', 'computer/technical', 'research (scientific)'],
          activity: "collecting data with (measurable) indicators to develop or test ideas/beliefs"
        },
        {
          name: 'systematic observation',
          skills: ['research (observation)', 'research (experimentation)'],
          activity: "using authoritative resources, systematic measuring of qualitative and quantitative measures (controlling the experiment to minimize alternative influences)"
        },
        {
          name: 'experimentation',
          skills: ['research', 'research (experimentation)'],
          activity: "testing a hypothesis/ideas"
        },
        {
          name: 'tools (data collection)',
          skills: ['computer/technical'],
          activity: "using appropriate tools to collect data with"
        },
        {
          name: 'simulation',
          skills: ['computer/technical', 'research (experimentation)'],
          activity: "running experiments by use of computerised tools"
        },
        {
          name: 'data storage',
          skills: ['computer/technical'],
          activity: "collecting evidence"
        },
        {
          name: 'data security',
          skills: ['computer/technical'],
          activity: "taking privacy of data into consideration"
        },
        {
          name: 'documentation',
          skills: ['communication (language)', 'computer/technical', 'communication (writing)'],
          activity: "careful record keeping of methods and findings"
        },
        {
          name: 'classification',
          skills: ['critical thinking', 'analytical'],
          activity: "organising the data into themes or categories"
        },
        {
          name: 'reflection on data collection',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "reflecting on what knowledge was gained and what has not been collected yet, and examining and evaluating this aspect of the learning experience"
        }
    ]
  },
  {
    name: 'data analysis',
    tasks: [
        {
          name: 'quantitative',
          skills: ['analytical (quantitative analysis)', 'analytical (statistical)', 'analytical (mathematical)'],
          activity: "processing measures taken"
        },
        {
          name: 'qualitative',
          skills: ['analytical (statistical)', 'analytical (mathematical)', 'analytical (qualitative analysis)'],
          activity: "processing the measured perceptions of phenomena, e.g. extracting theme's, clustering"
        },
        {
          name: 'tools (data analysis)',
          skills: ['computer/technical'],
          activity: "using data analysis tools, like spreadsheets, tables"
        },
        {
          name: 'visualisation',
          skills: ['critical thinking (comprehension)', 'computer/technical'],
          activity: "using graphs or other visualisations"
        },
        {
          name: 'noise reduction',
          skills: ['analytical', 'critical thinking (evaluation)'],
          activity: "get rid of faulty data (not the disconfirming data, ofcourse)"
        },
        {
          name: 'reflection on data analysis',
          skills: ['critical thinking', 'critical thinking (evaluation)', 'metacognitive'],
          activity: "checking the analyses and coming up with alternatives, and examining and evaluating this aspect of the learning experience"
        }

    ]
  },
  {
    name: 'interpretation',
    tasks: [
        {
          name: 'embedding',
          skills: ['critical thinking (comprehension)', 'information literacy (existing knowledge, learning)', 'critical thinking (inferring)', 'analytical (classification)'],
          activity: "interpreting findings in light of previous knowledge"
        },
        {
          name: 'confirmation/falsification',
          skills: ['critical thinking (evaluation)', 'research (experimentation)', 'analytical (statistical)', 'analytical (mathematical)'],
          activity: "judging evidence and counterevidence"
        },
        {
          name: 'significance',
          skills: ['critical thinking', 'analytical', 'analytical (statistical)', 'analytical (mathematical)'],
          activity: "making sure the results did not come by chance"
        },
        {
          name: 'relevance',
          skills: ['critical thinking', 'analytical'],
          activity: "making sure the results are relevant to the problem"
        },
        {
          name: 'threshold',
          skills: ['analytical', 'analytical (statistical)', 'analytical (mathematical)'],
          activity: "checking if an effect only occurs above a certain threshold"
        },
        {
          name: 'exhaustion',
          skills: ['critical thinking', 'analytical', 'critical thinking (evaluation)'],
          activity: "making sure you have not missed anything"
        },
        {
          name: 'relflection of interpretation',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "checking the interpretation (process) and coming up with alternatives, and examining and evaluating this aspect of the learning experience"
        }
    ]
  },
  {
    name: 'communication',
    tasks: [
        {
          name: 'writing up',
          skills: ['communication (language)', 'communication (writing)'],
          activity: "presenting the findings in clear, written form"
        },
        {
          name: 'strategy',
          skills: ['research (planning)', 'research (organisation)'],
          activity: "considering impact, content, routes and stakeholders"
        },
        {
          name: 'audience',
          skills: ['analytical', 'analytical (classification)', 'research (planning)'],
          activity: "determining the audience and adjusting presentation mode accordingly"
        },
        {
          name: 'tools',
          skills: ['computer/technical'],
          activity: "using technical tools for communicating results"
        },
        {
          name: 'dissemination',
          skills: ['communication (language)', 'communication (writing)', 'communication (presentation)', 'communication'],
          activity: "spreading your findings, getting them noticed"
        },
        {
          name: 'discussion',
          skills: ['communication', 'critical thinking'],
          activity: "discussing the findings in a critical manner (e.g. implicaitons, limitations of approach, lessons for future studies)"
        },
        {
          name: 'feedback',
          skills: ['communication'],
          activity: "providing feedback on findings of others"
        },
        {
          name: 'reflection',
          skills: ['critical thinking', 'metacognitive', 'critical thinking (evaluation)'],
          activity: "checking the method of communication and coming up with alternative approaches, and examining and evaluating this aspect of the learning experience"
        }
    ]
  }

]

File.open('skills.json', 'w') do |f|
  f.write JSON.pretty_generate(phases)
end

