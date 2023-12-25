import networkx as nx

#with open('input/test.txt', 'r') as file:
with open('input/input.txt', 'r') as file:
    lines = file.readlines()

G = nx.Graph()

for line in lines:
    if line.startswith('#') or not line.strip():
        continue

    components = line.strip().split(':')
    source = components[0].strip()
    targets = components[1].split()

    G.add_edges_from([(source, target) for target in targets])

min_cut = nx.stoer_wagner(G)

group1 = min_cut[1][0]
group2 = min_cut[1][1]

group1_size = len(group1)
group2_size = len(group2)

# Multiplier la somme des deux groupes
result = group1_size * group2_size

# Afficher le résultat
print(result)